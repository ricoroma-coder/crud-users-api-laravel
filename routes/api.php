<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('form')
    ->group(
        function () {
            Route::post('/store', Array(UserController::class, 'store'))
                ->middleware('route.validation.store')
                ->name('storeUser');
            Route::post('/store/{id}', Array(UserController::class, 'store'))
                ->middleware('route.validation.update')
                ->name('updateUser');
        }
    );
