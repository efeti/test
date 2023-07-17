<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required',
        //     'phone' => 'required|digits:11',
        //     'email' => 'required|email|regex:/^(?:[a-zA-Z0-9]+(?:\.[a-zA-Z0-9]+)*)@(?:gmail|yahoo)\.(?:com)$/i',
        // ]);

        // if ($validator->fails()) {
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        // return $next($request);

    }
}
