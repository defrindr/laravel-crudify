<?php

namespace Defrindr\Crudify\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UnprocessableEntityHttpException extends HttpException
{
    public function __construct(string $message = '', ?\Throwable $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct(422, $message, $previous, $headers, $code);
    }
}
