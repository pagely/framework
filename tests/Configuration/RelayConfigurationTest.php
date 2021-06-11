<?php

namespace EquipTests\Configuration;

use Equip\Configuration\AurynConfiguration;
use Equip\Configuration\RelayConfiguration;
use Equip\Configuration\DefaultMiddlewareConfiguration;
use Laminas\Diactoros\Response;
use Relay\Relay;

class RelayConfigurationTest extends ConfigurationTestCase
{
    protected function getConfigurations()
    {
        return [
            new AurynConfiguration,
            new RelayConfiguration,
            new DefaultMiddlewareConfiguration,
        ];
    }

    public function testApply()
    {
        $dispatcher = $this->injector->make(Relay::class);

        $this->assertInstanceOf(Relay::class, $dispatcher);
    }
}
