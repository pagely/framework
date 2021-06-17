<?php
namespace EquipTests\Handler;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Stream;
use Laminas\Diactoros\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Relay\Relay;
use Closure;

abstract class ContentHandlerTestCase extends HandlerTestCase
{
    protected function getRequestWithBody(string $mime, string $body): RequestInterface
    {
        $stream = new Stream('php://memory', 'w+');
        $stream->write($body);
        return new ServerRequest(
            $server  = [],
            $upload  = [],
            $path    = '/',
            $method  = 'POST',
            $body    = $stream,
            $headers = [
                'Content-Type' => $mime,
            ]
        );
    }
}
