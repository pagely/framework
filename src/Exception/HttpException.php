<?php

namespace Equip\Exception;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class HttpException extends RuntimeException
{
    public static function notFound(string $path): HttpException
    {
        return new HttpException(sprintf(
            'Cannot find any resource at `%s`',
            $path
        ), 404);
    }

    public static function methodNotAllowed(string $path, string $method, array $allowed): HttpException
    {
        $error = new HttpException(sprintf(
            'Cannot access resource `%s` using method `%s`',
            $path,
            $method
        ), 405);

        $error->allowed = $allowed;

        return $error;
    }

    public static function badRequest(string $message): HttpException
    {
        return new HttpException(sprintf(
            'Cannot parse the request: %s',
            $message
        ), 400);
    }

    private array $allowed = [];

    public function withResponse(ResponseInterface $response): ResponseInterface
    {
        if (!empty($this->allowed)) {
            $response = $response->withHeader('Allow', implode(',', $this->allowed));
        }

        return $response;
    }
}
