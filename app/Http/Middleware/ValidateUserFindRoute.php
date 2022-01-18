<?php

namespace App\Http\Middleware;

use App\Models\ApiMessage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ValidateUserFindRoute
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
        $validator = Validator::make(['id' => $request->id], [
            'id' => ['required']
        ]);

        if ($validator->fails())
        {
            $response = new ApiMessage(400, $validator->errors()->first());
            return $response->throwMessage();
        }

        return $next($request);
    }
}
