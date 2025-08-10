<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = null, string $message = 'Request processed successfully', int $statusCode = 200)
    {
        return response()->json([
            'statusCode' => $statusCode,
            'message'    => $message,
            'data'       => $data,
            'timestamp'  => now()->toISOString(),
        ], $statusCode);
    }

    public static function created($data = null, string $message = 'Resource created successfully')
    {
        return self::success($data, $message, 201);
    }

    /**
     * Envía respuesta de error.
     * @param string|array $errorMessage Puede ser string o array con detalles de error.
     * @param int $statusCode Código HTTP.
     */
    public static function error($errorMessage, int $statusCode = 400)
    {
        $payload = [
            'statusCode' => $statusCode,
            'timestamp'  => now()->toISOString(),
        ];

        if (is_array($errorMessage)) {
            // Cuando es un array (por ejemplo, errores de validación)
            $payload['errors'] = $errorMessage;
        } else {
            // Cuando es un string
            $payload['error'] = $errorMessage;
        }

        return response()->json($payload, $statusCode);
    }
}
