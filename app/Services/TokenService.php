<?php

// app/Services/TokenService.php

namespace App\Services;

use App\Models\Token;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TokenService
{
    // Método para crear un token de verificación de un solo uso
    public static function createVerificationToken($userId)
    {
        $token = Str::random(64); // Genera un token aleatorio de 64 caracteres
        
        return Token::create([
            'user_id' => $userId,
            'token' => $token,  // Aquí guardamos el token aleatorio
            'expires_at' => Carbon::now()->addMinutes(10), // Expira en 10 minutos
        ]);
    }

    // Método para validar el token
    public static function validateToken($token)
    {
        $tokenRecord = Token::where('token', $token)->first();

        // Si no existe o el token ha expirado
        if (!$tokenRecord || $tokenRecord->expires_at <= Carbon::now()) {
            return false;
        }

        return $tokenRecord->user;
    }
}
