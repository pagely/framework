<?php

namespace EquipTests\Configuration;

use Auryn\Injector;
use Equip\Configuration\AurynConfiguration;

class AurynConfigurationTest extends ConfigurationTestCase
{
    protected function getConfigurations(): array
    {
        return [
            new AurynConfiguration,
        ];
    }

    public function testApply(): void
    {
        // Injector is not a singleton
        $injector = $this->injector->make(Injector::class);
        $this->assertNotSame($injector, $this->injector);
    }
}
