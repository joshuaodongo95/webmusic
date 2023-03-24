<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('auth', [AuthController::class, 'google_redirect']);
Route::get('auth/callback', [AuthController::class, 'google_callback']);

Route::group(['middleware' => ['cors', 'json.response']], function () {

    // ...

    // public routes
    Route::post('/login', [AuthController::class, 'login'])->name('login.api');
    Route::post('/register',[AuthController::class, 'register'])->name('register.api');
    
    // ...

    Route::middleware('auth:api')->group(function () {
        // our routes to be protected will go in here
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout.api');
    });

});
