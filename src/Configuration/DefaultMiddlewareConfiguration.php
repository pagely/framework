<?php

namespace Equip\Configuration;

use Auryn\Injector;
use Equip\Adr\PayloadInterface;
use Equip\Middleware\MiddlewareSet;
use Equip\Payload;
use Laminas\Diactoros\Response;

class DefaultMiddlewareConfiguration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function apply(Injector $injector)
    {
        $set = new MiddlewareSet([
            function() {
                return new Response();
            }
        ]);
        $injector->share($set);
    }
}

