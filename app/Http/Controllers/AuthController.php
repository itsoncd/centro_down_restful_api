<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // Registrar un nuevo usuario
    public function register(Request $request)
    {
        try {
            $response = $this->authService->register($request->all());
            return ApiResponse::created($response, 'Usuario registrado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 400);
        }
    }

    // Login
    public function login(Request $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);
            $response = $this->authService->login($credentials);
            return ApiResponse::success($response, 'Login exitoso', 200);
        } catch (\Exception $e) {
            $code = ($e->getCode() > 0) ? $e->getCode() : 400;
            return ApiResponse::error($e->getMessage(), $code);
        }
    }

    // Verificar token de correo electrónico
    public function verifyEmailToken(Request $request, $token)
    {
        try {
            $response = $this->authService->verifyEmailToken($token);
            return ApiResponse::success($response, 'Token verificado exitosamente');
        } catch (\Exception $e) {
            $code = ($e->getCode() > 0) ? $e->getCode() : 400;
            return ApiResponse::error($e->getMessage(), $code);
        }
    }

    // Reenviar token de verificación
    public function resendVerificationToken(Request $request)
    {
        try {
            $response = $this->authService->resendVerificationToken($request->only('email'));
            return ApiResponse::success($response, 'Token reenviado exitosamente');
        } catch (ValidationException $e) {
            return ApiResponse::error($e->validator->errors(), 422);
        } catch (\Exception $e) {
            $code = ($e->getCode() >= 400 && $e->getCode() < 600) ? $e->getCode() : 400;
            return ApiResponse::error($e->getMessage(), $code);
        }
    }

    // Obtener usuario autenticado
    public function getUser()
    {
        $user = $this->authService->getUser();
        return ApiResponse::success($user);
    }

    // Logout
    public function logout()
    {
        $this->authService->logout();
        return ApiResponse::success(null, 'Logout exitoso');
    }
}
