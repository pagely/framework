<?php

namespace Equip\Middleware;

use Equip\Exception\MiddlewareException;
use Equip\Structure\Set;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareSet extends Set
{
    /**
     * Middleware can follow the MiddlwareInterface, RequestHandlerInterface
     * or be a callable
     *
     * https://relayphp.com/
     *
     * @throws MiddlewareException
     *  If $classes does not conform to type expectations.
     */
    protected function assertValid(array $classes)
    {
        parent::assertValid($classes);

        foreach ($classes as $middleware) {
            if (
                !is_a($middleware, MiddlewareInterface::class, true)
                && !is_a($middleware, RequestHandlerInterface::class, true)
                && !is_callable($middleware)) {

                throw MiddlewareException::notValidMiddleware($middleware);
            }
        }
    }
}
