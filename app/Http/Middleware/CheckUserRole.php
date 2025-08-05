<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if(!auth()->check()){
            return redirect()->route('login');
        }
        $user=auth()->user();
        if(!in_array($user->role, $roles)){
            abort(403, 'Доступ запрещен');
            // abort(404, 'Не найдено');
            //return redirect()->route('home');
        }

        return $next($request);
    }
}
