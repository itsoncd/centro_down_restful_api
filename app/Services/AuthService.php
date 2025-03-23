<?php

// app/Services/AuthService.php

// app/Services/AuthService.php

namespace App\Services;

use App\Models\User;
use App\Models\Token;
use App\Mail\TestEmail;
use App\Services\TokenService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\URL;

class AuthService
{
    // Método para registrar un nuevo usuario
    public function register(array $data)
    {
        // Validación de los datos
        $validated = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user,teacher',
        ]);

        if ($validated->fails()) {
            throw new ValidationException($validated);
        }

        // Verificación de existencia de usuario
        $existingUser = User::where('email', $data['email'])->first();
        if ($existingUser) {
            throw new \Exception('El correo electrónico ya está registrado.');
        }

        // Crear el usuario
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'confirmed' => false, // El usuario no está verificado inicialmente
        ]);

        // Crear el token de verificación
        $token = TokenService::createVerificationToken($user->id);

        // Enviar el correo con el enlace de verificación
        $frontendUrl = env('APP_FRONTEND_URL', 'http://localhost:3000');
        $verificationUrl = $frontendUrl . '/verify-email/' . $token->token;

        Mail::to($user->email)->send(new TestEmail($verificationUrl));

        return [
            'message' => 'Usuario registrado exitosamente. Token de verificación enviado.',
            'token' => $token->token, // Token de verificación
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'confirmed' => $user->confirmed, // Por defecto es false
                'created_at' => $user->created_at->toDateTimeString(), // Fecha de creación
            ]
        ];
    }

    // Método para login y generar un token JWT
    public function login(array $credentials)
    {
        // Validación de las credenciales
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Intentamos autenticar al usuario
        $user = User::where('email', $credentials['email'])->first();

        // Si el usuario no existe
        if (!$user) {
            throw new \Exception('El correo electrónico no está registrado.', 404);
        }

        // Verificar si el usuario está confirmado
        if (!$user->confirmed) {
            throw new \Exception('El correo electrónico no ha sido verificado.', 400);
        }

        // Verificación de la contraseña
        if (!Hash::check($credentials['password'], $user->password)) {
            throw new \Exception('La contraseña es incorrecta.', 401);
        }


        // Generación del token JWT
        try {
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            throw new \Exception('No se pudo generar el token.', 500);
        }

        // Enviar un correo de confirmación al iniciar sesión (opcional)
        // Mail::to($user->email)->send(new TestEmail($user->email));

        return $token;
    }

    // Método para verificar el token de un solo uso
    public function verifyEmailToken(string $token)
    {
        // Verificar si el token existe en la base de datos
        $verificationToken = Token::where('token', $token)->first();

        if (!$verificationToken) {
            throw new \Exception('Token de verificación no válido.', 400);
        }

        // Verificar si el token ha expirado
        if (!$verificationToken->isValid()) {
            throw new \Exception('El token ha expirado.', 400);
        }

        // Obtener el usuario asociado al token
        $user = $verificationToken->user;

        // Verificar si el usuario ya está confirmado
        if ($user->confirmed) {
            throw new \Exception('El usuario ya está confirmado.', 400);
        }

        // Marcar al usuario como confirmado
        $user->confirmed = true;
        $user->save();

        // Eliminar el token después de ser validado
        $verificationToken->delete();

        return [
            'message' => 'Correo electrónico verificado exitosamente.',
            'user' => $user,
        ];
    }

    public function resendVerificationToken(array $data)
    {
        // Validar que el correo sea un email válido
        $validator = Validator::make($data, [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Buscar usuario
        $user = User::where('email', $data['email'])->first();

        // Verificar si el usuario ya confirmó su correo
        if ($user->confirmed) {
            throw new \Exception('Tu cuenta ya está activada.', 400);
        }

        // Eliminar cualquier token anterior del usuario
        Token::where('user_id', $user->id)->delete();

        // Crear un nuevo token de verificación
        $token = Token::create([
            'user_id' => $user->id,
            'token' => bin2hex(random_bytes(32)), // Genera un token único
            'expires_at' => now()->addMinutes(10), // Expira en 10 minutos
        ]);

        // Enviar el correo con el nuevo token
        $frontendUrl = env('APP_FRONTEND_URL', 'http://localhost:3000');
        $verificationUrl = $frontendUrl . '/verify-email/' . $token->token;

        Mail::to($user->email)->send(new TestEmail($verificationUrl));

        return [
            'message' => 'Se ha enviado un nuevo token de verificación a tu correo.',
            'email' => $user->email,
        ];
    }


    // Método para obtener el usuario autenticado
    public function getUser()
    {
        return \Illuminate\Support\Facades\Auth::user();
    }

    // Método para cerrar sesión
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }
}
