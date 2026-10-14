<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSystemSettingsRequest;
use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemSettingsController extends Controller
{
    public function index(): View
    {
        $settings = SettingsService::all();

        return view('admin.settings.index', [
            'settings' => $settings,
        ]);
    }

    public function update(UpdateSystemSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // handle logo upload
        if ($request->hasFile('system_logo')) {
            $file = $request->file('system_logo');
            $path = $this->storeAsWebp($file, 'system/logo');

            // delete old logo if exists
            $old = SettingsService::get('system_logo');
            if ($old) {
                Storage::disk('public')->delete($old);
            }

            SettingsService::set('system_logo', $path, 'string', 'branding');
        }

        // simple scalar settings
        $scalars = [
            'company_name', 'max_upload_limit', 'max_upload_size_mb', 'default_sla_hours', 'default_language'
        ];

        foreach ($scalars as $key) {
            if (array_key_exists($key, $validated)) {
                SettingsService::set($key, (string) ($validated[$key] ?? ''));
            }
        }

        SettingsService::set('enable_notifications', $validated['enable_notifications'] ?? false);
        SettingsService::set('enable_dark_mode', $validated['enable_dark_mode'] ?? false);

        // qr_defaults as json
        if (isset($validated['qr_defaults'])) {
            SettingsService::set('qr_defaults', json_encode($validated['qr_defaults']));
        }

        activity()
            ->causedBy($request->user())
            ->withProperties(['changed' => array_keys($validated)])
            ->log('Updated system settings.');

        return back()->with('status', 'System settings updated.');
    }

    protected function storeAsWebp($file, string $directory): string
    {
        $directory = trim($directory, '/');
        $baseName = sprintf('%s-%s.webp', time(), uniqid());
        $storagePath = "{$directory}/{$baseName}";

        $tmpPath = $file->path();

        $image = @imagecreatefromstring(file_get_contents($tmpPath));
        if (! $image) {
            abort(422, 'Uploaded image could not be processed.');
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $scale = min(1200 / $width, 1200 / $height, 1);
        $newWidth = max(1, (int) round($width * $scale));
        $newHeight = max(1, (int) round($height * $scale));

        $canvas = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);

        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefilledrectangle($canvas, 0, 0, $newWidth, $newHeight, $transparent);
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        if (! function_exists('imagewebp')) {
            abort(500, 'Server must support WebP conversion.');
        }

        $full = storage_path('app/public/'.$storagePath);
        $dir = dirname($full);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        imagewebp($canvas, $full, 78);
        imagedestroy($image);
        imagedestroy($canvas);

        return $storagePath;
    }
}
