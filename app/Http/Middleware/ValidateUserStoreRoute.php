<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ApiMessage;

class ValidateUserStoreRoute
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
        ]);

        if ($validator->fails())
        {
            $response = new ApiMessage(400, $validator->errors()->first());
            return $response->throwMessage();
        }

        return $next($request);
    }
}
