<?php

namespace Equip\Handler;

use Equip\Directory;
use Equip\Exception\HttpException;
use Equip\Handler\ActionHandler;
use FastRoute;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DispatchHandler implements MiddlewareInterface
{
    private Directory $directory;

    public function __construct(Directory $directory)
    {
        $this->directory = $directory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        list($action, $args) = $this->dispatch(
            $this->dispatcher(),
            $request->getMethod(),
            $request->getUri()->getPath()
        );

        $request = $request->withAttribute(ActionHandler::ACTION_ATTRIBUTE, $action);

        foreach ($args as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }

        $r = $handler->handle($request);
        return $r;
    }

    protected function dispatcher(): Dispatcher
    {
        return FastRoute\simpleDispatcher(function (RouteCollector $collector) {
            foreach ($this->directory as $request => $action) {
                list($method, $path) = explode(' ', $request, 2);

                $collector->addRoute(
                    $method,
                    $this->directory->prefix($path),
                    $action
                );
            }
        });
    }

    private function dispatch(Dispatcher $dispatcher, string $method, string $path): array
    {
        $route = $dispatcher->dispatch($method, $path);
        $status = array_shift($route);

        if (Dispatcher::FOUND === $status) {
            return $route;
        }

        if (Dispatcher::METHOD_NOT_ALLOWED === $status) {
            $allowed = array_shift($route);
            throw HttpException::methodNotAllowed($path, $method, $allowed);
        }

        throw HttpException::notFound($path);
    }
}
