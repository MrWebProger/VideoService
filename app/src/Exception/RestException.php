<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RestException extends HttpException
{
    public function __construct(
        string|array $message = '',
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        array $headers = [],
        \Throwable $previous = null,
        int $code = 0
    ) {
        $message = is_array($message) ? json_encode($message) : $message;
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
