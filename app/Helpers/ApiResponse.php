<?php

namespace App\Helpers;

class ApiResponse
{
    
    public static function success($data = null, $message = 'Operación exitosa', $code = 200)
    {
        return response()->json([
            'status_code' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public static function error($errors, $message = 'Error', $code = 400)
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

}
