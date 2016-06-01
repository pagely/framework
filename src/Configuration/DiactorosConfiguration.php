<?php

namespace Equip\Configuration;

use Auryn\Injector;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\ServerRequestFactory;

class DiactorosConfiguration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function apply(Injector $injector)
    {
        $injector->alias(
            ResponseInterface::class,
            Response::class
        );

        $injector->alias(
            ServerRequestInterface::class,
            ServerRequest::class
        );

        $injector->delegate(
            ServerRequest::class,
            [ServerRequestFactory::class, 'fromGlobals']
        );
    }
}
