<?php

namespace Equip\Configuration;

use Auryn\Injector;
use Equip\Adr\PayloadInterface;
use Equip\Handler\ActionHandler;
use Equip\Handler\DispatchHandler;
use Equip\Handler\ResponseEmitterHandler;
use Equip\Middleware\MiddlewareSet;
use Equip\Payload;
use Laminas\Diactoros\Response;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Http\Message\ResponseInterface;

class DefaultMiddlewareConfiguration implements ConfigurationInterface
{
    public function apply(Injector $injector)
    {
        $injector->alias(EmitterInterface::class, SapiEmitter::class);
        $injector->alias(ResponseInterface::class, Response::class);
        $set = new MiddlewareSet([
            $injector->make(ResponseEmitterHandler::class),
            $injector->make(DispatchHandler::class),
            $injector->make(ActionHandler::class)
        ]);
        $injector->share($set);
    }
}

