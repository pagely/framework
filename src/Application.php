<?php

namespace Equip;

use Auryn\Injector;
use Equip\Configuration\ConfigurationSet;
use Equip\Directory;
use Equip\Middleware\MiddlewareSet;
use Relay\Relay;

final class Application
{
    /**
     * Create a new application
     *
     * @param Injector $injector
     * @param ConfigurationSet $configuration
     * @param MiddlewareSet $middleware
     *
     * @return static
     */
    public static function build(
        Injector $injector = null,
        ConfigurationSet $configuration = null,
        MiddlewareSet $middleware = null
    ): Application {
        return new Application($injector, $configuration, $middleware);
    }

    private Injector $injector;
    private ConfigurationSet $configuration;
    private MiddlewareSet $middleware;

    /**
     * @var callable|string
     */
    private mixed $routing = "";

    /**
     * @param Injector $injector
     * @param ConfigurationSet $configuration
     * @param MiddlewareSet $middleware
     */
    public function __construct(
        Injector $injector = null,
        ConfigurationSet $configuration = null,
        MiddlewareSet $middleware = null
    ) {
        $this->injector = $injector ?: new Injector;
        $this->configuration = $configuration ?: new ConfigurationSet;
        $this->middleware = $middleware ?: new MiddlewareSet;
    }

    /**
     * Change configuration values
     *
     * @param array $configuration
     *
     * @return self
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $this->configuration->withValues($configuration);
        return $this;
    }

    /**
     * Change middleware
     *
     * @param array $middleware
     *
     * @return self
     */
    public function setMiddleware(array $middleware)
    {
        $this->middleware = $this->middleware->withValues($middleware);
        return $this;
    }

    /**
     * Change routing
     *
     * @param callable|string $routing
     */
    public function setRouting($routing): Application
    {
        $this->routing = $routing;
        return $this;
    }

    /**
     * Run the application
     *
     * @param string $runner
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function run($runner = Relay::class)
    {
        $this->configuration->apply($this->injector);

        return $this->injector
            ->share($this->middleware)
            ->prepare(Directory::class, $this->routing)
            ->execute($runner);
    }
}
