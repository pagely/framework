<?php

namespace EquipTests\Configuration;

use Auryn\Injector;
use PHPUnit\Framework\TestCase;

abstract class ConfigurationTestCase extends TestCase
{
    /**
     * @var Injector
     */
    protected $injector;

    /**
     * @return array
     */
    abstract protected function getConfigurations();

    public function setUp(): void
    {
        $this->applyConfigurations();
    }

    /**
     * @return void
     */
    protected function applyConfigurations()
    {
        $this->injector = new Injector;

        foreach ($this->getConfigurations() as $config) {
            $config->apply($this->injector);
        }
    }
}
