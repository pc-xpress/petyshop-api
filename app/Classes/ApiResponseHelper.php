<?php

namespace App\Classes;

use Dotenv\Exception\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiResponseHelper
{
    /**
     * Rollback the database transaction and throw an exception.
     *
     * @param  \Throwable  $e The exception that caused the rollback.
     * @param  string  $message  The message to include in the exception.
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public static function rollback($e, $message = 'Failure in de process')
    {
        // Rollback the database transaction.
        DB::rollback(); // Rollback the database transaction.

        // Throw an exception with the given message.
        self::throw($e, $message); // Throw an exception with the given message.
    }

    /**
     * Throw an exception with the given message.
     *
     * @param \Throwable $e The exception that caused the rollback.
     * @param string $message The message to include in the exception.
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public static function throw($e, $message = 'Failure in de process')
    {
        // Log the exception.
        Log::info($e);

        // Throw an HttpResponseException with a JSON response containing the message.
        // The JSON response will have a status code of 500.
        throw new HttpResponseException(response()->json([
            'message' => $message
        ], 500));
    }

    /**
     * Send a JSON response with the given data, success status, message, errors, and status code.
     *
     * @param  mixed  $result The data to include in the response.
     * @param  bool  $success The success status of the response. Defaults to true.
     * @param  string  $message The message to include in the response. Defaults to an empty string.
     * @param  array  $errors The errors to include in the response. Defaults to an empty array.
     * @param  int  $code The status code of the response. Defaults to 200.
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public static function sendResponse(
        mixed $result,
        bool $success = true,
        string $message = '',
        array $errors = [],
        int $code = 200
    ): \Illuminate\Http\JsonResponse {
        // If the status code is 204, return a no content response.
        if ($code === 204) {
            return response()->noContent();
        }

        // Create the response array.
        $response = [
            'success' => $success, // The success status of the response.
            'status' => $code, // The status code of the response.
            'errors' => $errors, // The errors to include in the response.
            'message' => $message, // The message to include in the response.
            'data' => $result, // The data to include in the response.
        ];

        // If a message is provided, overwrite the message in the response array.
        if (!empty($message)) {
            $response['message'] = $message;
        }

        // If errors are provided, overwrite the errors in the response array.
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        // Return a JSON response with the response array and the status code.
        return response()->json($response, $code);
    }
}
