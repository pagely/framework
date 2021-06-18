<?php

namespace EquipTests\Configuration;

use Auryn\Injector;

trait ConfigurationTestTrait
{
    protected Injector $injector;

    abstract protected function getConfigurations(): array;

    public function setUp(): void
    {
        $this->applyConfigurations();
    }

    protected function applyConfigurations(): void
    {
        $this->injector = new Injector;

        foreach ($this->getConfigurations() as $config) {
            $config->apply($this->injector);
        }
    }
}
