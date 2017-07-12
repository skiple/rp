<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->isAdmin == 0) {
            // return Unauthorized (401) if the authenticated user is not an admin
            $json = array(
                'status'  => 0,
                'message' => "Unauthorized"
            );

            return response()->json($json, 401);
        }
        
        return $next($request);
    }
}
