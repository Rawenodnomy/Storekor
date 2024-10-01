<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()){
            if (auth()->user()->is_admin == 1){
                // return $next($request);
                // dd('пытается зайти админ');
                return redirect('/admin/home', 302);
            }
            return Redirect('home')->with('error', 'У вас нет доступа на страницу администратора');
        } else {
            dd('войдите в систему');
        }
       
    }
}
