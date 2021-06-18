<?php

namespace EquipTests\Configuration;

use Equip\Configuration\EnvConfiguration;
use Equip\Env;
use Equip\Exception\EnvException;
use josegonzalez\Dotenv\Loader;

class EnvConfigurationTest extends ConfigurationTestCase
{
    /**
     * @var string
     */
    private $envfile;

    public function setUp(): void
    {
        if (!class_exists(Loader::class)) {
            $this->markTestSkipped('Dotenv is not installed');
        }

        $this->envfile = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . '.env';
    }

    protected function getConfigurations(): array
    {
        return [
            new EnvConfiguration,
        ];
    }

    public function testApply()
    {
        $this->createEnv();
        $this->applyConfigurations();

        $env = $this->injector->make(Env::class);

        $this->assertInstanceOf(Env::class, $env);
        $this->assertTrue($env['test']);

        $this->destroyEnv();
    }

    public function testUnableToDetect()
    {
        $this->expectException(EnvException::class);
        $this->expectExceptionMessageMatches('/unable to automatically detect/i');
        $config = new EnvConfiguration;
    }

    public function testInvalidRoot()
    {
        $this->expectException(EnvException::class);
        $this->expectExceptionMessageMatches('/environment file .* does not exist/i');
        $config = new EnvConfiguration('/tmp/bad/path/.env');
    }

    private function createEnv(): void
    {
        file_put_contents($this->envfile, 'test=true');
    }

    private function destroyEnv(): void
    {
        unlink($this->envfile);
    }
}
