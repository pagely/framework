<?php

namespace EquipTests\Configuration;

use Equip\Configuration\DiactorosConfiguration;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;

class DiactorosConfigurationTest extends ConfigurationTestCase
{
    protected function getConfigurations()
    {
        return [
            new DiactorosConfiguration
        ];
    }

    public function dataMapping()
    {
        return [
            [ResponseInterface::class, Response::class],
            [ServerRequestInterface::class, ServerRequest::class]
        ];
    }

    /**
     * @dataProvider dataMapping
     */
    public function testInstances($interface, $class)
    {
        $instance = $this->injector->make($interface);
        $this->assertInstanceOf($class, $instance);
    }
}
