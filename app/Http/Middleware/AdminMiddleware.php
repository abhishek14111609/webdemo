<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return $this->handleUnauthenticated($request);
        }

        // Check if user is an admin
        if (!Auth::user()->is_admin) {
            return $this->handleUnauthorized($request);
        }

        // Add security headers
        $response = $next($request);
        
        // Add security headers to all admin responses
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Prevent caching of admin pages
        $response->headers->addCacheControlDirective('no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }

    /**
     * Handle unauthenticated users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    protected function handleUnauthenticated(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'redirect' => route('login')
            ], Response::HTTP_UNAUTHORIZED);
        }

        return redirect()
            ->guest(route('login'))
            ->with('error', 'Please log in to access the admin area.');
    }

    /**
     * Handle unauthorized users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    protected function handleUnauthorized(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'You do not have permission to access this resource.',
                'required_role' => 'admin'
            ], Response::HTTP_FORBIDDEN);
        }

        return redirect()
            ->route('home')
            ->with('error', 'You do not have permission to access the admin area.');
    }
}