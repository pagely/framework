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
    protected function t(ServerRequest $request, mixed $middleware, Closure $asserter, $assertType = 'request'): ResponseInterface
    {
        $chain = [];

        if (!is_array($middleware)) {
            $middleware = [$middleware];
        }

        switch($assertType) {
        case 'request':
            foreach($middleware as $m) {
                $chain[] = $m;
            }
            $chain[] = $asserter;
            break;
        case 'response':
            $chain[] = $asserter;
            foreach($middleware as $m) {
                $chain[] = $m;
            }
            break;
        default:
            throw new \Exception("Unknown assertType: $assertType");
        }

        $chain[] = function() {
            return new Response();
        };

        $relay = new Relay($chain);

        return $relay->handle($request);
    }

    protected function getRequest(string $method = 'GET', string $path = '/'): ServerRequestInterface
    {
        return (new ServerRequest)
            ->withMethod($method)
            ->withUri(new Uri($path));
    }

}
