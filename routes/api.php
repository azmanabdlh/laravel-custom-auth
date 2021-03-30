<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\UserRegistrationController;

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

Route::group(['middleware' => 'auth:jwt'], function() {
    Route::get('/home', function(Request $request){
        return Auth::user();
    })->name('api.home');
});


Route::get('/login', function(){
    return 'login view';
})->name('login');

Route::post('/login', [LoginController::class, 'handle']);
Route::post('/user', [UserRegistrationController::class, 'handle'])->name('register');
