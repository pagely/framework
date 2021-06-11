<?php

namespace Equip\Configuration;

use Auryn\Injector;
use Equip\Resolver\Resolver;

class AurynConfiguration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function apply(Injector $injector)
    {
        $injector->define(Resolver::class, [
            ':injector' => $injector,
        ]);
    }
}
