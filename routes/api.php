<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Api\AuthController;

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

// Route with role check (requires 'admin' role)
Route::middleware(['auth.token', 'role:admin'])->get('/role-check', function () {
    return response()->json(['message' => 'Role check passed.']);
});

Route::middleware('role:admin')->get('admin/dashboard', function () {
    return response()->json(['message' => 'Welcome to Admin Dashboard.']);
});

