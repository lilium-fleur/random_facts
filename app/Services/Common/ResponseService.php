<?php

namespace App\Services\Common;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use Throwable;

class ResponseService
{

    public static function make(
        bool   $success = true,
        mixed  $result = null,
        ?string $message = null,
        array  $errors = [],
        int    $statusCode = 200,
    ): JsonResponse {
        $body = [
            'success' => $success,
            'message' => $message,
            'errors'  => $errors ?: null,
            'result'    => $result,
        ];

        $body = array_filter($body, static fn ($value) => $value !== null);

        return response()->json($body, $statusCode);
    }


    public static function success(
        mixed $result = null,
        ?string $message = null,
    ): JsonResponse {
        return self::make(true, $result, $message, [], 200);
    }

    public static function unSuccess(
        mixed $result = null,
        ?string $message = null,
    ): JsonResponse {
        return self::make(true, $result, $message, [], 200);
    }


    public static function created(
        mixed $result = null,
        string $message = 'Created',
    ): JsonResponse {
        return self::make(true, $result, $message, [],201);
    }


    public static function error(
        ?string $message = 'Error',
        array $errors = [],
        int $statusCode = 400,
    ): JsonResponse {
        return self::make(false, null, $message, $errors, $statusCode);
    }

    public static function badRequest(
        ?string $message = 'Bad request',
        array $errors = [],
    ): JsonResponse {
        return self::error($message, $errors, 400);
    }

    public static function unauthorized(
        ?string $message = 'Unauthorized',
        array $errors = [],
    ): JsonResponse {
        return self::error($message, $errors, 401);
    }

    public static function forbidden(
        ?string $message = 'Forbidden',
        array $errors = [],
    ): JsonResponse {
        return self::error($message, $errors, 403);
    }

    public static function notFound(
        ?string $message = 'Not Found',
        array $errors = [],
    ): JsonResponse {
        return self::error($message, $errors, 404);
    }


    public static function validation(
        MessageBag|array $errors,
        string           $message = 'Validation failed'
    ): JsonResponse {
        $errorsArray = $errors instanceof MessageBag ? $errors->toArray() : $errors;

        return self::error($message, $errorsArray, 422);
    }

    public static function fromException(
        Throwable $e,
        int       $code = 500,
        ?string   $message = null
    ): JsonResponse {
        Log::error($e);

        return self::error(
            $message ?? 'Server error',
            [
                'exception' => class_basename($e),
                'message'   => $e->getMessage(),
            ],
            $code
        );
    }
}
