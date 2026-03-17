<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TeachersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authcheck = Auth::guard('teacher')->check();

        Log::info('Teacher authentication check', [
            'auth_check' => $authcheck,
            'id' => Auth::guard('teacher')->id(),
            'email' => Auth::guard('teacher')->user() ? Auth::guard('teacher')->user()->email : null,
        ]);

        if (!$authcheck) {
            Log::warning('Unauthorized access attempt by teacher', [
                'ip' => $request->ip(),
                'url' => $request->url(),
            ]);
            return redirect()->route('teacher.login')->with(['message' => 'You must be logged in as a teacher to access this page.']);
        }

        return $next($request);


    }
}
