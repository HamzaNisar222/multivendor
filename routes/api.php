<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\ServiceController;
use App\Http\Controllers\api\SubServicesController;

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
Route::post('/register', [AuthController::class, 'register'])->middleware('validation:register');
Route::get('register/confirm/{token}', [AuthController::class, 'confirmEmail'])->name('register.confirm');
Route::post('/login', [AuthController::class, 'login'])->middleware('validation:login');
Route::post('/logout', [AuthController::class, 'logout']);

// Route to get all services
Route::get('/services', [ServiceController::class, 'index']);

// Route to get all Sub Services related to main Services
Route::get('/sub-services/{serviceId}', [SubServicesController::class, 'index']);

// Route with role check (requires 'admin' role)
Route::middleware(['auth.token', 'role:admin'])->group(function() {
    //Route to create Main Service, update, delete
    Route::post('/admin/services', [ServiceController::class, 'store']);
    Route::put('/admin/services/{id}', [ServiceController::class, 'update']);
    Route::delete('/admin/services/{id}', [ServiceController::class, 'destroy']);

    //Route to create Sub Services, update, delete
    Route::post('/sub-services/{serviceId}', [SubServicesController::class, 'store']);
    Route::put('/sub-services/{id}', [SubServicesController::class, 'update']);
    Route::delete('/sub-services/{id}', [SubServicesController::class, 'destroy']);
});

// Route with role check (required 'vendor' role)
Route::middleware(['auth.token', 'role:vendor'])->group(function() {
    //Route for the vendor to requet for the services
});
