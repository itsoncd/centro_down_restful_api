<?php

// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\TokenService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Stmt\TryCatch;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // Método para registrar un nuevo usuario
    public function register(Request $request)
    {
        try {
            // Llamar al servicio para registrar el usuario
            $response = $this->authService->register($request->all());

            return response()->json($response, 201); // Responder con los datos del usuario y token

        } catch (\Exception $e) {
            // En caso de que se lance una excepción (usuario ya registrado o validación fallida)
            return response()->json([
                'error' => $e->getMessage()
            ], 400); // Código de error 400
        }
    }


    // Método para login
    public function login(Request $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);
            $response = $this->authService->login($credentials);

            return response()->json([
                'message' => 'Login exitoso',
                'token' => $response['token'],
                'user' => $response['user'],
            ], 200);
        } catch (\Exception $e) {
            $code = ($e->getCode() > 0) ? $e->getCode() : 400;
            return response()->json(['error' => $e->getMessage()], $code);
        }
    }

    // Método para verificar el token de correo electrónico
    public function verifyEmailToken(Request $request, $token)
    {
        try {
            // Llamamos al servicio para verificar el token
            $response = $this->authService->verifyEmailToken($token);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    // Metodo para reenviar un token nuevo por usuario
    public function resendVerificationToken(Request $request)
{
    try {
        $response = $this->authService->resendVerificationToken($request->only('email'));
        return response()->json($response, 200);
    } catch (ValidationException $e) {
        return response()->json([
            'errors' => $e->validator->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 400);
    }
}


    // Método para obtener el usuario autenticado
    public function getUser()
    {
        $user = $this->authService->getUser();
        return response()->json($user, 200);
    }

    // Método para hacer logout
    public function logout()
    {
        $this->authService->logout();
        return response()->json(['message' => 'Logout exitoso'], 200);
    }
}
