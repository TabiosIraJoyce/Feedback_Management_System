<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePublicFeedbackRequest;
use App\Models\Branch;
use App\Models\Complaint;
use App\Models\Department;
use App\Models\Feedback;
use App\Models\FeedbackTicket;
use App\Models\FormSubmission;
use App\Models\QrCode;
use App\Services\QrCodeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PublicFeedbackController extends Controller
{
    public function __construct(
        private QrCodeService $qrCodes
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | PUBLIC FEEDBACK INDEX
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $branches = Branch::active()
            ->with([
                'departments' => fn ($query) =>
                    $query
                        ->where('status', 'active')
                        ->orderBy('name')
            ])
            ->orderBy('name')
            ->get();

        return view('public-feedback.index', [
            'branches' => $branches,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW PUBLIC FEEDBACK FORM
    |--------------------------------------------------------------------------
    */

    public function show(
        Request $request,
        string $branch,
        ?string $department = null
    ): View {

        [
            $branchModel,
            $departmentModel,
            $qrCode
        ] = $this->resolveFeedbackTarget(
            $branch,
            $department
        );

        $branchModel->loadMissing(
            'publicForm.fields'
        );


        /*
        |--------------------------------------------------------------------------
        | Record QR Scan
        |--------------------------------------------------------------------------
        */

        if (
            (int) $request
                ->session()
                ->pull('skip_qr_scan_id')
            !== $qrCode->id
        ) {
            $this->qrCodes->recordScan(
                $qrCode,
                $request
            );
        }


        return view('public-feedback.show', [
            'branch' => $branchModel,
            'department' => $departmentModel,
            'qrCode' => $qrCode,
            'currentLocale' => app()->getLocale(),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | STORE PUBLIC FEEDBACK
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        string $branch,
        ?string $department = null
    ): RedirectResponse {

        [
            $branchModel,
            $departmentModel,
            $qrCode
        ] = $this->resolveFeedbackTarget(
            $branch,
            $department
        );

        $branchModel->loadMissing(
            'publicForm.fields'
        );


        /*
        |--------------------------------------------------------------------------
        | Dynamic Public Form
        |--------------------------------------------------------------------------
        */

        if (
            $branchModel->publicForm?->is_active
            &&
            $branchModel->publicForm->fields->isNotEmpty()
        ) {
            return $this->storeDynamicForm(
                $request,
                $branchModel,
                $departmentModel,
                $qrCode
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Standard Feedback Form
        |--------------------------------------------------------------------------
        */

        $formRequest = app(
            StorePublicFeedbackRequest::class
        );

        $validated = Validator::make(
            $request->all(),
            $formRequest->rules(),
            $formRequest->messages(),
        )->validate();


        /*
        |--------------------------------------------------------------------------
        | Process Photos
        |--------------------------------------------------------------------------
        */

        $photos = [];

        if ($request->hasFile('photos')) {
            $photos = $this->processPhotos(
                $request->file('photos'),
                $branchModel->slug
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Category
        |--------------------------------------------------------------------------
        */

        $satisfaction = $validated['satisfaction']
            ?? $this->resolveSatisfaction(
                $validated['rating']
                ?? null
            );

        $category = $validated['category']
            ?? ($satisfaction === 'dislike' ? 'complaint' : 'feedback');


        /*
        |--------------------------------------------------------------------------
        | Description
        |--------------------------------------------------------------------------
        */

        $description = trim(
            (string) (
                $validated['description']
                ?? ''
            )
        );

        if ($description === '') {
            $description = __(
                'No written details provided.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Subject
        |--------------------------------------------------------------------------
        */

        $subject = trim(
            (string) (
                $validated['subject']
                ?? ''
            )
        );

        if ($subject === '') {
            $subject = $this->defaultSubject(
                $satisfaction
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Customer Information
        |--------------------------------------------------------------------------
        */

        $customerName =
            $validated['customer_name']
            ?? $request->input('name')
            ?? null;

        $customerEmail =
            $validated['customer_email']
            ?? $request->input('email')
            ?? null;

        $customerPhone =
            $validated['customer_phone']
            ?? $request->input('phone')
            ?? null;


        /*
        |--------------------------------------------------------------------------
        | Create Feedback Ticket
        |--------------------------------------------------------------------------
        */

        $ticket = FeedbackTicket::create([

            'branch_id' => $branchModel->id,

            'department_id' =>
                $departmentModel?->id,

            'qr_code_id' =>
                $qrCode->id,

            'satisfaction' =>
                $satisfaction,

            'category' =>
                $category,

            'subject' =>
                $subject,

            'comment' =>
                $description,

            'description' =>
                $description,

            'rating' =>
                $validated['rating']
                ?? null,

            'customer_name' =>
                $customerName,

            'customer_email' =>
                $customerEmail,

            'customer_phone' =>
                $customerPhone,

            'photos' =>
                $photos,

            'status' =>
                'pending',

            'is_anonymous' =>
                empty($customerName),

            'source' =>
                'qr',

            'hci_metadata' =>
                $this->buildHciMetadata(
                    $validated
                ),
        ]);

        $submission = $this->createRoutedSubmission(
            $satisfaction,
            $branchModel,
            $departmentModel,
            $subject,
            $category,
            $description,
            $customerName,
            $customerEmail,
            $customerPhone
        );

        $this->attachProcessedPhotos(
            $submission,
            $photos,
            $request->file('photos', [])
        );


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT:
        | Activity logging removed because the project does not currently
        | have the activity() helper available.
        |--------------------------------------------------------------------------
        */


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate QR Scan
        |--------------------------------------------------------------------------
        */

        $request->session()->flash(
            'skip_qr_scan_id',
            $qrCode->id
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect Back to Feedback Form
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'feedback.public.show',
            [
                'branch' =>
                    $branchModel->slug,

                'department' =>
                    $departmentModel?->slug,
            ]
        )
            ->with(
                'status',
                "Thank you! Your ticket number is {$ticket->reference_no}."
            )
            ->with(
                'feedback_success',
                [
                    'reference_no' =>
                        $ticket->reference_no,

                    'type' =>
                        'ticket',
                ]
            );
    }

    /**
     * Track a feedback ticket by reference number (public API used by page tracker)
     */
    public function track(Request $request): JsonResponse
    {
        $reference = trim((string) $request->input('reference'));
        $query = trim((string) $request->input('query'));

        // If explicit reference provided, prefer exact lookup
        if ($reference !== '') {
            $ref = strtoupper($reference);

            $ticket = FeedbackTicket::where('reference_no', $ref)->first();

            if (! $ticket) {
                return response()->json(['error' => 'Ticket not found.'], 404);
            }

            return response()->json([
                'reference_no' => $ticket->reference_no,
                'status' => $ticket->status,
                'branch' => $ticket->branch?->name,
                'department' => $ticket->department?->name,
                'created_at' => $ticket->created_at?->toIso8601String(),
            ]);
        }

        // Otherwise allow free-text lookup (phone/email/name/partial ref)
        $q = $query;

        if ($q === '') {
            return response()->json(['error' => 'Please provide a reference or phone/email/name to search.'], 422);
        }

        $like = '%' . $q . '%';

        $matches = FeedbackTicket::query()
            ->where('reference_no', 'like', $like)
            ->orWhere('customer_phone', 'like', $like)
            ->orWhere('customer_email', 'like', $like)
            ->orWhere('customer_name', 'like', $like)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['reference_no', 'status', 'branch_id', 'department_id', 'created_at', 'customer_name', 'customer_phone', 'customer_email']);

        if ($matches->isEmpty()) {
            return response()->json(['error' => 'No tickets found matching that query.'], 404);
        }

        // Hydrate branch/department names for matches
        $results = $matches->map(function ($t) {
            return [
                'reference_no' => $t->reference_no,
                'status' => $t->status,
                'branch' => $t->branch?->name,
                'department' => $t->department?->name,
                'created_at' => $t->created_at?->toIso8601String(),
                'customer_name' => $t->customer_name,
                'customer_phone' => $t->customer_phone,
                'customer_email' => $t->customer_email,
            ];
        })->values();

        return response()->json(['matches' => $results]);
    }
    /**
 * Show a customer's submitted feedback.
 */
public function showSubmission(string $reference): View
{
    $reference = strtoupper(trim($reference));

    $ticket = FeedbackTicket::with([
        'branch',
        'department',
    ])
        ->where('reference_no', $reference)
        ->firstOrFail();

    return view('public-feedback.submission', [
        'ticket' => $ticket,
    ]);
}

    private function createRoutedSubmission(
        ?string $satisfaction,
        Branch $branch,
        ?Department $department,
        string $subject,
        string $category,
        string $description,
        ?string $customerName,
        ?string $customerEmail,
        ?string $customerPhone
    ): Model {
        if ($satisfaction === 'dislike') {
            return Complaint::create([
                'branch_id' => $branch->id,
                'department_id' => $department?->id,
                'submitted_by_user_id' => null,
                'customer_name' => $customerName ?: 'Anonymous Customer',
                'customer_email' => $customerEmail,
                'customer_phone' => $customerPhone,
                'subject' => $subject,
                'category' => $category ?: 'general',
                'priority' => 'normal',
                'status' => 'open',
                'message' => $description,
                'sentiment' => 'negative',
                'allow_public_reopen' => true,
            ]);
        }

        return Feedback::create([
            'branch_id' => $branch->id,
            'department_id' => $department?->id,
            'user_id' => null,
            'name' => $customerName ?: 'Anonymous Customer',
            'email' => $customerEmail,
            'subject' => $subject,
            'type' => 'customer',
            'status' => 'submitted',
            'message' => $description,
            'sentiment' => 'positive',
        ]);
    }

    private function attachProcessedPhotos(
        Model $submission,
        array $photos,
        array|UploadedFile|null $files
    ): void {
        if (! method_exists($submission, 'attachments') || empty($photos)) {
            return;
        }

        $files = is_array($files) ? array_values($files) : [$files];

        foreach (array_values($photos) as $index => $photo) {
            $file = $files[$index] ?? null;
            $path = $photo['path'] ?? null;

            if (! $path) {
                continue;
            }

            $submission->attachments()->create([
                'uploaded_by_user_id' => null,
                'disk' => 'public',
                'directory' => dirname($path),
                'path' => $path,
                'stored_name' => basename($path),
                'original_name' => $file instanceof UploadedFile
                    ? $file->getClientOriginalName()
                    : basename($path),
                'extension' => pathinfo($path, PATHINFO_EXTENSION) ?: 'webp',
                'mime_type' => 'image/webp',
                'size_bytes' => $file instanceof UploadedFile
                    ? (int) $file->getSize()
                    : 0,
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | STORE DYNAMIC FORM
    |--------------------------------------------------------------------------
    */

    private function storeDynamicForm(
        Request $request,
        Branch $branch,
        ?Department $department = null,
        ?QrCode $qrCode = null
    ): RedirectResponse {

        $rules = [];


        /*
        |--------------------------------------------------------------------------
        | Build Validation Rules
        |--------------------------------------------------------------------------
        */

        foreach (
            $branch->publicForm->fields
            as $field
        ) {

            $rule = [];


            if ($field->required) {

                $rule[] = 'required';

            } else {

                $rule[] = 'nullable';

            }


            switch ($field->type) {

                case 'email':

                    $rule[] = 'email';

                    break;


                case 'tel':

                    $rule[] = 'string';

                    break;


                case 'number':

                    $rule[] = 'numeric';

                    break;


                case 'date':

                    $rule[] = 'date';

                    break;


                case 'checkbox':

                    $rule[] = 'array';
                    $rule[] = 'min:1';

                    break;


                default:

                    $rule[] = 'string';

                    break;
            }


            if (
                $field->type === 'checkbox'
            ) {

                $rules[$field->name] =
                    $rule;

                $rules[$field->name . '.*'] =
                    ['string'];

            } else {

                $rules[$field->name] =
                    $rule;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | HCI Context
        |--------------------------------------------------------------------------
        */

        $rules['hci_context'] = [
            'nullable',
            'json',
        ];


        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            $rules
        );


        /*
        |--------------------------------------------------------------------------
        | Build Payload
        |--------------------------------------------------------------------------
        */

        $payload = $validated;


        if (
            !empty(
                $validated['hci_context']
            )
        ) {

            $payload['_hci'] =
                json_decode(
                    $validated['hci_context'],
                    true
                ) ?: [];

            unset(
                $payload['hci_context']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Form Submission
        |--------------------------------------------------------------------------
        */

        FormSubmission::create([

            'form_id' =>
                $branch->publicForm->id,

            'branch_id' =>
                $branch->id,

            'department_id' =>
                $department?->id,

            'payload' =>
                $payload,

            'status' =>
                'new',
        ]);


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT:
        | Activity logging removed here as well.
        |--------------------------------------------------------------------------
        */


        /*
        |--------------------------------------------------------------------------
        | QR Scan Session
        |--------------------------------------------------------------------------
        */

        if ($qrCode) {

            $request->session()->flash(
                'skip_qr_scan_id',
                $qrCode->id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'feedback.public.show',
            [
                'branch' =>
                    $branch->slug,

                'department' =>
                    $department?->slug,
            ]
        )
            ->with(
                'status',
                'Thank you! Your responses have been recorded.'
            )
            ->with(
                'feedback_success',
                [
                    'reference_no' =>
                        null,

                    'type' =>
                        'form',
                ]
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RESOLVE BRANCH / DEPARTMENT / QR CODE
    |--------------------------------------------------------------------------
    */

    private function resolveFeedbackTarget(
        string $branch,
        ?string $department = null
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Find Branch
        |--------------------------------------------------------------------------
        */

        $branchModel = Branch::query()
            ->where(
                'slug',
                $branch
            )
            ->where(
                'status',
                'active'
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Department QR
        |--------------------------------------------------------------------------
        */

        if ($department !== null) {

            $departmentModel =
                Department::with('branch')
                    ->where(
                        'branch_id',
                        $branchModel->id
                    )
                    ->where(
                        'slug',
                        $department
                    )
                    ->where(
                        'status',
                        'active'
                    )
                    ->firstOrFail();


            $qrCode =
                $departmentModel->qrCode
                ?? $this->qrCodes
                    ->syncForDepartment(
                        $departmentModel
                    );


            abort_if(
                $qrCode->status !== 'active',
                404
            );


            return [
                $branchModel,
                $departmentModel,
                $qrCode,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Branch QR
        |--------------------------------------------------------------------------
        */

        $qrCode =
            $branchModel->qrCode
            ?? $this->qrCodes
                ->syncForBranch(
                    $branchModel
                );


        abort_if(
            $qrCode->status !== 'active',
            404
        );


        return [
            $branchModel,
            null,
            $qrCode,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | PROCESS PHOTOS
    |--------------------------------------------------------------------------
    */

    private function processPhotos(
        array $files,
        string $branchSlug
    ): array {

        $saved = [];


        $directory =
            storage_path(
                'app/public/feedback-photos'
            );


        $thumbnailDirectory =
            $directory . '/thumbnails';


        /*
        |--------------------------------------------------------------------------
        | Create Directories
        |--------------------------------------------------------------------------
        */

        if (!is_dir($directory)) {

            mkdir(
                $directory,
                0755,
                true
            );
        }


        if (!is_dir($thumbnailDirectory)) {

            mkdir(
                $thumbnailDirectory,
                0755,
                true
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Process Each Photo
        |--------------------------------------------------------------------------
        */

        foreach ($files as $file) {

            $baseName = sprintf(
                '%s-%s-%s.webp',
                $branchSlug,
                time(),
                uniqid()
            );


            $previewName =
                'thumb-' . $baseName;


            $photoPath =
                $directory . '/' . $baseName;


            $thumbnailPath =
                $thumbnailDirectory .
                '/' .
                $previewName;


            /*
            |--------------------------------------------------------------------------
            | Save Main Image
            |--------------------------------------------------------------------------
            */

            $this->saveWebpImage(
                $file->path(),
                $photoPath,
                1200,
                1200,
                78
            );


            /*
            |--------------------------------------------------------------------------
            | Save Thumbnail
            |--------------------------------------------------------------------------
            */

            $this->saveWebpImage(
                $photoPath,
                $thumbnailPath,
                300,
                300,
                72
            );


            $saved[] = [

                'path' =>
                    'feedback-photos/' .
                    $baseName,

                'thumbnail_path' =>
                    'feedback-photos/thumbnails/' .
                    $previewName,
            ];
        }


        return $saved;
    }


    /*
    |--------------------------------------------------------------------------
    | SAVE WEBP IMAGE
    |--------------------------------------------------------------------------
    */

    private function saveWebpImage(
        string $sourcePath,
        string $destinationPath,
        int $maxWidth,
        int $maxHeight,
        int $quality
    ): void {

        $image = @imagecreatefromstring(
            file_get_contents(
                $sourcePath
            )
        );


        if (!$image) {

            abort(
                422,
                'Uploaded photo could not be processed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Original Dimensions
        |--------------------------------------------------------------------------
        */

        $width =
            imagesx($image);

        $height =
            imagesy($image);


        /*
        |--------------------------------------------------------------------------
        | Calculate Scale
        |--------------------------------------------------------------------------
        */

        $scale = min(
            $maxWidth / $width,
            $maxHeight / $height,
            1
        );


        $newWidth =
            max(
                1,
                (int) round(
                    $width * $scale
                )
            );


        $newHeight =
            max(
                1,
                (int) round(
                    $height * $scale
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Create Canvas
        |--------------------------------------------------------------------------
        */

        $canvas =
            imagecreatetruecolor(
                $newWidth,
                $newHeight
            );


        imagealphablending(
            $canvas,
            false
        );


        imagesavealpha(
            $canvas,
            true
        );


        /*
        |--------------------------------------------------------------------------
        | Transparent Background
        |--------------------------------------------------------------------------
        */

        $transparent =
            imagecolorallocatealpha(
                $canvas,
                0,
                0,
                0,
                127
            );


        imagefilledrectangle(
            $canvas,
            0,
            0,
            $newWidth,
            $newHeight,
            $transparent
        );


        /*
        |--------------------------------------------------------------------------
        | Resize Image
        |--------------------------------------------------------------------------
        */

        imagecopyresampled(
            $canvas,
            $image,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );


        /*
        |--------------------------------------------------------------------------
        | WebP Support
        |--------------------------------------------------------------------------
        */

        if (!function_exists('imagewebp')) {

            abort(
                500,
                'Server must support WebP image conversion.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Save WebP
        |--------------------------------------------------------------------------
        */

        imagewebp(
            $canvas,
            $destinationPath,
            $quality
        );


        /*
        |--------------------------------------------------------------------------
        | Free Memory
        |--------------------------------------------------------------------------
        */

        imagedestroy($image);

        imagedestroy($canvas);
    }


    /*
    |--------------------------------------------------------------------------
    | BUILD HCI METADATA
    |--------------------------------------------------------------------------
    */

    private function buildHciMetadata(
        array $validated
    ): array {

        $context = [];


        if (
            !empty(
                $validated['hci_context']
            )
        ) {

            $context =
                json_decode(
                    $validated['hci_context'],
                    true
                ) ?: [];
        }


        return array_filter(

            [

                'locale' =>
                    app()->getLocale(),

                'selected_rating' =>
                    $validated['rating']
                    ?? null,

                'recovery_expectation' =>
                    $validated[
                        'recovery_expectation'
                    ]
                    ?? null,

                'service_highlight' =>
                    $validated[
                        'service_highlight'
                    ]
                    ?? null,

                'follow_up_channel' =>
                    $validated[
                        'follow_up_channel'
                    ]
                    ?? null,

                'client' =>
                    $context,
            ],

            fn ($value) =>
                $value !== null
                &&
                $value !== []
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DEFAULT SUBJECT
    |--------------------------------------------------------------------------
    */

    private function defaultSubject(
        ?string $satisfaction
    ): string {

        return match ($satisfaction) {

            'like' =>
                __('Positive feedback submission'),

            'dislike' =>
                __('Customer complaint submission'),

            default =>
                __('General feedback submission'),
        };
    }


    /*
    |--------------------------------------------------------------------------
    | RESOLVE SATISFACTION
    |--------------------------------------------------------------------------
    */

    private function resolveSatisfaction(
        ?int $rating
    ): ?string {

        if ($rating === null) {
            return null;
        }


        if ($rating >= 4) {
            return 'like';
        }


        if ($rating <= 2) {
            return 'dislike';
        }


        return null;
    }
}
