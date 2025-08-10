<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserAuth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (No Auth Required)
|--------------------------------------------------------------------------
*/
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('auth/send-token', [AuthController::class, 'resendVerificationToken']);
Route::get('auth/verify-email/{token}', [AuthController::class, 'verifyEmailToken']);

/*
|--------------------------------------------------------------------------
| Private Routes (Require Auth)
|--------------------------------------------------------------------------
*/
Route::middleware([IsAdmin::class])->group(function () {

    // Auth Actions
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('auth/user', [AuthController::class, 'getUser']);

    // Roles
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('{id}', [RoleController::class, 'show']);
        Route::put('{id}', [RoleController::class, 'update']);
        Route::delete('{id}', [RoleController::class, 'destroy']);
    });

    // Citas
    Route::prefix('citas')->group(function () {
        Route::get('/', [CitaController::class, 'index']);
        Route::post('/', [CitaController::class, 'store']);
        Route::get('{id}', [CitaController::class, 'show']);
        Route::put('{id}', [CitaController::class, 'update']);
        Route::delete('{id}', [CitaController::class, 'destroy']);
    });

    // Usuarios
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('{id}', [UserController::class, 'show']);
        Route::put('{id}', [UserController::class, 'update']);
        Route::patch('{id}/activate', [UserController::class, 'activate']);
        Route::patch('{id}/desactivate', [UserController::class, 'deactivate']);
    });
});
