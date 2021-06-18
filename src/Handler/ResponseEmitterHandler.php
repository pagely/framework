<?php

namespace Equip\Handler;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * The emitter should be the first thing in your middleware stack
 *
 * It allows all other middleware to run, and then emits the final response
 */
class ResponseEmitterHandler implements MiddlewareInterface
{
    private EmitterInterface $emitter;

    public function __construct(EmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $r = $handler->handle($request);

        $this->emitter->emit($r);
        return $r;
    }
}
