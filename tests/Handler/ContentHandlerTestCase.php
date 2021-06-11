<?php
namespace EquipTests\Handler;

use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Stream;
use Laminas\Diactoros\Response;
use Psr\Http\Server\MiddlewareInterface;
use Relay\Relay;
use Closure;

abstract class ContentHandlerTestCase extends TestCase
{
    /**
     * @param string $mime
     * @param string $body
     * @return ServerRequest
     */
    protected function getRequest($mime, $body)
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

    protected function t(ServerRequest $request, MiddlewareInterface $middleware, Closure $asserter): void
    {
        $relay = new Relay([
            $middleware,
            $asserter,
            function() {
                return new Response();
            },
        ]);

        $relay->handle($request);
    }
}
