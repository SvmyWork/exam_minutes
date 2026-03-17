<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $authcheck = Auth::guard('student')->check();

        Log::info('student authentication check', [
            'auth_check' => $authcheck,
            'student_id' => Auth::guard('student')->id(),
            'email' => Auth::guard('student')->user() ? Auth::guard('student')->user()->email : null,
        ]);

        if (!$authcheck) {
            Log::warning('Unauthorized access attempt by student', [
                'ip' => $request->ip(),
                'url' => $request->url(),
            ]);
            return redirect()->route('student.login')->withErrors(['error' => 'You must be logged in as a student to access this page.']);
        }

        return $next($request);
    }
}
