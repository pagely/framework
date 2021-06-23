<?php

namespace Equip\Configuration;

use Auryn\Injector;
use Equip\Middleware\MiddlewareSet;
use Equip\Resolver\Resolver;
use Relay\Relay;

class RelayConfiguration implements ConfigurationInterface
{
    public function apply(Injector $injector): void
    {

        $factory = function (MiddlewareSet $queue): Relay {
            return new Relay($queue);
        };

        $injector->delegate(Relay::class, $factory);
    }
}
