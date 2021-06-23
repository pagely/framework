<?php

namespace EquipTests;

use Auryn\Injector;
use Equip\Application;
use Equip\Configuration\ConfigurationInterface;
use Equip\Configuration\ConfigurationSet;
use Equip\Directory;
use Equip\Dispatching\DispatchingSet;
use Equip\Middleware\MiddlewareSet;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\MiddlewareInterface;
use ReflectionObject;
use Relay\Relay;

class ApplicationTest extends TestCase
{
    private function assertApplication($app)
    {
        $appObject = new ReflectionObject($app);

        $props = [
            'injector' => Injector::class,
            'configuration' => ConfigurationSet::class,
            'middleware' => MiddlewareSet::class,
            'dispatching' => DispatchingSet::class,
        ];

        foreach ($props as $name => $expected) {
            $prop = $appObject->getProperty($name);
            $prop->setAccessible(true);
            $value = $prop->getValue($app);

            if ($expected) {
                $this->assertInstanceOf($expected, $value, $name);
            }

            $props[$name] = $value;
        }
    }

    public function testBuild()
    {
        $app = Application::build();
        $this->assertApplication($app);
    }

    public function testCreate()
    {
        $injector = $this->createMock(Injector::class);
        $configuration = $this->createMock(ConfigurationSet::class);
        $middleware = $this->createMock(MiddlewareSet::class);
        $dispatching = $this->createMock(DispatchingSet::class);

        $app = new Application($injector, $configuration, $middleware, $dispatching);

        $this->assertApplication($app);
    }

    public function testSetConfiguration()
    {
        $data = [
            $this->createMock(ConfigurationInterface::class),
        ];

        $configuration = $this->createMock(ConfigurationSet::class);
        $configuration
            ->expects($this->once())
            ->method('withValues')
            ->with($data)
            ->willReturn(clone $configuration);

        $app = new Application(null, $configuration);
        $app->setConfiguration($data);

        $this->assertApplication($app);
    }

    public function testSetMiddleware()
    {
        $data = [
            $this->createMock(MiddlewareInterface::class),
        ];

        $middleware = $this->createMock(MiddlewareSet::class);
        $middleware
            ->expects($this->once())
            ->method('withValues')
            ->with($data)
            ->willReturn(clone $middleware);

        $app = new Application(null, null, $middleware);
        $app->setMiddleware($data);

        $this->assertApplication($app);
    }

    public function testSetDispatching()
    {
        $data = [
            function ($directory) {
                return $directory;
            },
        ];

        $dispatching = $this->createMock(DispatchingSet::class);
        $dispatching
            ->expects($this->once())
            ->method('withValues')
            ->with($data)
            ->willReturn(clone $dispatching);

        $app = new Application(null, null, null, $dispatching);
        $app->setDispatching($data);

        $this->assertApplication($app);

    }
}
