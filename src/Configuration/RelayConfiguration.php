<?php

namespace Equip\Configuration;

use Auryn\Injector;
use Equip\Middleware\MiddlewareSet;
use Equip\Resolver\Resolver;
use Relay\Relay;
use Relay\RelayBuilder;

class RelayConfiguration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function apply(Injector $injector): void
    {

        $factory = function (RelayBuilder $builder, MiddlewareSet $queue, Resolver $resolver): Relay {
            return $builder->newInstance($queue, $resolver);
        };

        $injector->delegate(Relay::class, $factory);
    }
}
