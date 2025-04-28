<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    public function handle(Request $request, Closure $next, ...$types)
    {
        $user = $request->user();
        
        if (!$user || !in_array($user->userType->name, $types)) {
            abort(403, 'Akses tidak dibenarkan untuk peranan anda.');
        }

        return $next($request);
    }
}