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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//PUBLIC ROUTES

// Auth Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('auth/send-token', [AuthController::class, 'resendVerificationToken']);
Route::get('auth/verify-email/{token}', [AuthController::class, 'verifyEmailToken']);

// Role ROUTES
Route::prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'index']);
    Route::post('/', [RoleController::class, 'store']);
    Route::get('{id}', [RoleController::class, 'show']);
    Route::put('{id}', [RoleController::class, 'update']);
    Route::delete('{id}', [RoleController::class, 'destroy']);
});
// Cita ROUTES
Route::prefix('citas')->group(function () {
    Route::post('/', [CitaController::class, 'store']);
    Route::get('/', [CitaController::class, 'index']);
    Route::delete('{id}', [CitaController::class, 'destroy']);
    Route::put('{id}', [CitaController::class, 'update']);
    Route::get('{id}', [CitaController::class, 'show']);
});

// Alumno ROUTES
Route::prefix('alumnos')->group(function () {
    Route::get('/', [AlumnoController::class, 'index']);
    Route::post('/', [AlumnoController::class, 'store']);
    Route::get('{id}', [AlumnoController::class, 'show']);
    Route::put('{id}', [AlumnoController::class, 'update']);
    Route::delete('{id}', [AlumnoController::class, 'destroy']);
});
// Tutor ROUTES
Route::prefix('tutor')->group(function () {
    Route::get('/', [TutorController::class, 'index']);
    Route::post('/', [TutorController::class, 'store']);
    Route::get('{id}', [TutorController::class, 'show']);
    Route::put('{id}', [TutorController::class, 'update']);
    Route::delete('{id}', [TutorController::class, 'destroy']);
});
// User ROUTES
Route::middleware([IsUserAuth::class])->prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('{id}', [UserController::class, 'show']);
    Route::put('{id}', [UserController::class, 'update']);
    Route::patch('{id}/activate', [UserController::class, 'activate']);
    Route::patch('{id}/deactivate', [UserController::class, 'deactivate']);
});


//PRIVATE ROUTES
Route::middleware([IsUserAuth::class])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::get('auth/user', 'getUser');
    });

    Route::get('products', [ProductController::class, 'getProducts']);
});
