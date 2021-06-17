<?php
namespace EquipTests\Handler;

use Equip\Directory;
use Equip\Handler\DispatchHandler;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Uri;
use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Relay\Relay;
use Closure;

abstract class HandlerTestCase extends TestCase
{
    protected function t(ServerRequest $request, MiddlewareInterface $middleware, Closure $asserter): ResponseInterface
    {
        $relay = new Relay([
            $middleware,
            $asserter,
            function() {
                return new Response();
            },
        ]);

        return $relay->handle($request);
    }

    protected function getRequest(string $method = 'GET', string $path = '/'): ServerRequestInterface
    {
        return (new ServerRequest)
            ->withMethod($method)
            ->withUri(new Uri($path));
    }

}
