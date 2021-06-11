<?php

namespace EquipTests\Configuration;

use Auryn\Injector;
use Equip\Configuration\AurynConfiguration;

class AurynConfigurationTest extends ConfigurationTestCase
{
    protected function getConfigurations()
    {
        return [
            new AurynConfiguration,
        ];
    }

    public function testApply()
    {
        // Injector is not a singleton
        $injector = $this->injector->make(Injector::class);
        $this->assertNotSame($injector, $this->injector);
    }
}
