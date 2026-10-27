<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\QrCode;
use App\Services\QrCodeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QrManagementController extends Controller
{
    public function __construct(private QrCodeService $qrCodes)
    {
    }

    public function index(Request $request): View
    {
        $this->qrCodes->syncAll();

        $search = trim((string) $request->query('search', ''));
        $branchFilter = $request->query('branch');
        $type = (string) $request->query('type', 'all');
        $status = (string) $request->query('status', 'all');

        $qrCodes = QrCode::query()
            ->with(['branch', 'department'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder->where('code', 'like', "%{$search}%")
                        ->orWhere('path', 'like', "%{$search}%")
                        ->orWhereHas('branch', fn ($branch) => $branch->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%"))
                        ->orWhereHas('department', fn ($department) => $department->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%"));
                });
            })
            ->when($branchFilter, fn ($query) => $query->where('branch_id', $branchFilter))
            ->when($type !== 'all', fn ($query) => $query->where('type', $type))
            ->when($status !== 'all', fn ($query) => $query->where('status', $status))
            ->orderByDesc('scan_count')
            ->orderBy('code')
            ->paginate(12);

        return view('admin.qr.index', [
            'qrCodes' => $qrCodes,
            'branchOptions' => Branch::orderBy('name')->get(['id', 'name']),
            'type' => $type,
            'status' => $status,
        ]);
    }

    public function toggleStatus(QrCode $qrCode)
    {
        $qrCode->update([
            'status' => $qrCode->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('status', 'QR code status updated successfully.');
    }

    public function download(Request $request, QrCode $qrCode, string $format): Response
    {
        return $this->qrCodes->download(
            $qrCode->loadMissing(['branch', 'department']),
            $format,
            $request->query('disposition', 'attachment') === 'inline' ? 'inline' : 'attachment'
        );
    }

    public function print(QrCode $qrCode): View
    {
        $qrCode->loadMissing(['branch', 'department']);

        return view('admin.qr.print', [
            'qrCode' => $qrCode,
        ]);
    }
}
