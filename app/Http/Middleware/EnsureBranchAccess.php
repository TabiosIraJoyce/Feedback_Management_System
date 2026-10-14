<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EnsureBranchAccess
{
    public function handle(Request $request, Closure $next, string $parameter = 'complaint'): Response
    {
        /** @var User|null $user */
        $user = $request->user();

        if (! $user || ! $user->isScopedReviewer()) {
            return $next($request);
        }

        $record = $request->route($parameter);

        if (! $record || ! isset($record->branch_id)) {
            return $next($request);
        }

        if (! $user->canReviewScopedRecord($record)) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'You can only access records for your assigned scope.');
        }

        return $next($request);
    }
}
