<?php
namespace Tests;

use Auryn\Injector;
use Equip\Configuration\AurynConfiguration;
use Equip\Configuration\DefaultMiddlewareConfiguration;
use Equip\Configuration\DiactorosConfiguration;
use Equip\Configuration\RelayConfiguration;
use Equip\Configuration\ConfigurationSet;
use Equip\Middleware\MiddlewareSet;
use Equip\Dispatching\DispatchingSet;
use Equip\Application;
use PHPUnit\Framework\TestCase;
use Equip\Exception\HttpException;

class EndToEndTest extends TestCase
{
    public function testFourOhFour(): void
    {
        $injector = new Injector();
        $configSet = new ConfigurationSet([
            AurynConfiguration::class,
            DiactorosConfiguration::class,
            RelayConfiguration::class,
            DefaultMiddlewareConfiguration::class,
        ]);

        $configSet->apply($injector);
        $middleSet = $injector->make(MiddlewareSet::class);
        $dispatchSet = new DispatchingSet();

        $app = new Application($injector, $configSet, $middleSet, $dispatchSet);

        $this->expectException(HttpException::class);
        $this->expectExceptionCode(404);
        $app->run();
    }
}
