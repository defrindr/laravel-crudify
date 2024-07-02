<?php

namespace Defrindr\Crudify\Helpers;

use Illuminate\Support\MessageBag;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ResponseHelper
{
    protected static function response(mixed $content, int $statusCode)
    {
        // help debug
        if (config('app.env') !== 'production') {
            $content = array_merge($content, [
                'request' => request()->all(),
            ]);
        }

        $content['executionTime'] = round(microtime(true) - LARAVEL_START, 3);
        $content['timestamps'] = time();
        $content['environment'] = config('app.env');

        return response()->json($content, $statusCode);
    }

    public static function validationError(?MessageBag $errors, ?string $message = null)
    {
        $body = ['message' => $errors?->first() ?? $message ?? 'Bad request, please check your input', 'errors' => $errors];

        return static::response($body, 400);
    }

    public static function conflict(?string $message = null)
    {
        $body = ['message' => $message ?? 'Conflict, please check your input'];

        return static::response($body, 422);
    }

    public static function error(Throwable $throwable, ?string $message = null)
    {
        if ($throwable instanceof HttpException) {
            $message = $throwable->getMessage() ?? 'Terjadi kesalahan tidak terduga';
            $code = $throwable->getStatusCode();
        } else {
            $message = $message ?? $throwable->getMessage() ?? 'Terjadi kesalahan tidak terduga';
            $code = 500;
        }

        $body = [
            'message' => $message,
            'errors' => [
                'class' => get_class($throwable),
            ],
        ];

        if (config('app.env') !== "production") {
            $body['errors']['description'] = $throwable->getTrace();
        }

        return static::response($body, $code);
    }

    public static function unAuthorization(?string $message = 'UnAuthorization')
    {
        $body = ['message' => $message];

        return static::response($body, 403);
    }

    public static function unAuthencation()
    {
        $body = ['message' => 'UnAuthenticated'];

        return static::response($body, 401);
    }

    public static function badRequest(?string $message)
    {
        $body = ['message' => $message ?? 'Bad Request, Please check your input.'];

        return static::response($body, 400);
    }

    public static function success($body)
    {
        return static::response($body, 200);
    }

    public static function successWithData(mixed $data, ?string $message = 'Success get data', ?int $statusCode = 200)
    {
        return static::response(['message' => $message, 'data' => $data], $statusCode);
    }

    public static function modelNotFound(?string $message)
    {
        return static::response(['message' => $message ?? 'Data yang anda cari tidak tersedia.'], 404);
    }

    public static function methodNotAllowed(?string $message)
    {
        return static::response(['message' => $message ?? 'Data yang anda cari tidak tersedia.'], 405);
    }
}
