<?php

namespace App\Http\Middleware;

use App\Models\ApiMessage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ValidateUserUpdateRoute
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
        $values = array_merge(['id' => $request->id], $request->all());

        $validator = Validator::make($values, [
            'id' => ['required', 'min:1'],
            'name' => ['nullable', Rule::unique('users')->ignore($values['id'])],
            'address' => ['nullable'],
            'city' => ['nullable'],
            'state' => ['nullable'],
        ]);

        if ($validator->fails())
        {
            $response = new ApiMessage(400, $validator->errors()->first());
            return $response->throwMessage();
        }

        return $next($request);
    }
}
