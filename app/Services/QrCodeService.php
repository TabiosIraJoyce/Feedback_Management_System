<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Department;
use App\Models\QrCode;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class QrCodeService
{
    public function syncAll(): void
    {
        Branch::query()->get()->each(fn (Branch $branch) => $this->syncForBranch($branch));
        Department::query()->with('branch')->get()->each(fn (Department $department) => $this->syncForDepartment($department));
    }

    public function syncForBranch(Branch $branch): QrCode
    {
        return QrCode::updateOrCreate(
            ['type' => 'branch', 'branch_id' => $branch->id],
            [
                'department_id' => null,
                'code' => $this->branchCode($branch),
                'path' => 'feedback/'.$branch->slug,
                'status' => $this->defaultStatus($branch->status),
            ]
        );
    }

    public function syncForDepartment(Department $department): QrCode
    {
        $department->loadMissing('branch');

        return QrCode::updateOrCreate(
            ['type' => 'department', 'department_id' => $department->id],
            [
                'branch_id' => $department->branch_id,
                'code' => $this->departmentCode($department),
                'path' => 'feedback/'.$department->branch->slug.'/'.$department->slug,
                'status' => $this->defaultStatus($department->status),
            ]
        );
    }

    public function publicUrl(QrCode $qrCode): string
    {
        if ($qrCode->type === 'department' && $qrCode->department) {
            return route('feedback.public.show', [
                'branch' => $qrCode->branch?->slug ?? $qrCode->department->branch?->slug,
                'department' => $qrCode->department->slug,
            ]);
        }

        return route('feedback.public.show', [
            'branch' => $qrCode->branch?->slug,
        ]);
    }

    public function remoteImageUrl(QrCode $qrCode, string $format = 'svg', int $size = 700): string
    {
        return 'https://api.qrserver.com/v1/create-qr-code/?size='
            .$size.'x'.$size
            .'&format='.$format
            .'&data='.urlencode($this->publicUrl($qrCode));
    }

    public function download(QrCode $qrCode, string $format = 'svg', string $disposition = 'attachment'): Response
    {
        abort_unless(in_array($format, ['svg', 'png'], true), 404);

        try {
            $response = Http::timeout(20)
                ->accept($format === 'svg' ? 'image/svg+xml' : 'image/png')
                ->get($this->remoteImageUrl($qrCode, $format))
                ->throw();
        } catch (RequestException $exception) {
            abort(502, 'QR image provider is unavailable right now.');
        }

        if ($disposition === 'attachment') {
            $qrCode->forceFill([
                'download_count' => $qrCode->download_count + 1,
                'last_downloaded_at' => now(),
            ])->save();
        }

        $filename = Str::slug($qrCode->code).'.'.$format;

        return response($response->body(), 200, [
            'Content-Type' => $format === 'svg' ? 'image/svg+xml' : 'image/png',
            'Content-Disposition' => $disposition.'; filename="'.$filename.'"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    public function recordScan(QrCode $qrCode, Request $request): void
    {
        $qrCode->scans()->create([
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 65535, ''),
            'referer' => Str::limit((string) $request->headers->get('referer'), 65535, ''),
            'path' => '/'.ltrim($request->path(), '/'),
            'scanned_at' => now(),
        ]);

        $qrCode->forceFill([
            'scan_count' => $qrCode->scan_count + 1,
            'last_scanned_at' => now(),
        ])->save();
    }

    public function branchCode(Branch $branch): string
    {
        return 'GLD-'.$this->normalizeSegment($branch->code);
    }

    public function departmentCode(Department $department): string
    {
        $department->loadMissing('branch');

        return 'GLD-'.$this->normalizeSegment($department->branch->code).'-'.$this->normalizeSegment($department->code);
    }

    public function scanTrend(Collection $rows, int $days = 7): array
    {
        $indexed = $rows->pluck('total', 'date');
        $labels = [];
        $values = [];

        for ($offset = $days - 1; $offset >= 0; $offset--) {
            $date = now()->subDays($offset)->toDateString();
            $labels[] = now()->subDays($offset)->format('M j');
            $values[] = (int) ($indexed[$date] ?? 0);
        }

        return compact('labels', 'values');
    }

    private function normalizeSegment(string $value): string
    {
        return Str::upper(preg_replace('/[^A-Za-z0-9]+/', '-', trim($value)) ?: 'QR');
    }

    private function defaultStatus(string $status): string
    {
        return $status === 'inactive' ? 'inactive' : 'active';
    }
}
