<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {


        if ($request->all() != [] && !empty($request->all()['name'])){
            
            if (!preg_match("/^[A-Za-zА-ЯЁа-яё0-9]+$/u", $request->all()["name"])) {
                session(['errorSymbol' => 'Недопустимые символы']);
                return redirect('/register');
            } else {
                session()->forget('errorSymbol');
            }
        }
        // return redirect('/register');
        $guards = empty($guards) ? [null] : $guards;
        
        foreach ($guards as $guard) {
           
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        
        return $next($request);
    }
}
