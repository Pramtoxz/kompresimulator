<?php

namespace App\Http\Middleware;

use App\Models\Attempt;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAttemptBelongsToUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $attempt = $request->route('attempt');

        abort_unless($attempt instanceof Attempt, 404);
        abort_unless($attempt->user_id === $request->user()?->id, 403);

        return $next($request);
    }
}
