<?php

namespace App\Http\Middleware;

use Closure;
use App\Shorturl;

class DomainCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $code = $request->path();
        if (isset($code) && $code!= '') {
            $link = Shorturl::where('code',$code)->first('link');
            if ($link) {
                return redirect($link->link);
            }
        }
        return $next($request);
    }
}
