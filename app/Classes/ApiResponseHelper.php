<?php

namespace App\Classes;

use Dotenv\Exception\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiResponseHelper
{
    public static function rollback($e, $message = 'Failure in de process')
    {
        DB::rollback();
        self::throw($e, $message);
    }

    public static function throw($e, $message = 'Failure in de process')
    {
        Log::info($e);
        throw new HttpResponseException(response()->json([
            'message' => $message
        ], 500));
    }

    public static function sendResponse($result, $success = true, $message = '', $errors = [], $code = 200)
    {
        if ($code === 204) {
            return response()->noContent();
        }


        $response = [
            'success' => $success,
            'status' => $code,
            'errors' => $errors,
            'message' => $message,
            'data' => $result,
        ];

        if (!empty($message)) {
            $response['message'] = $message;
        }
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
