<?php

namespace Equip\Handler;

use Equip\Contract\ActionInterface;
use Equip\Resolver\ResolverTrait;
use Equip\Resolver\Resolver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ActionHandler implements MiddlewareInterface
{
    use ResolverTrait;

    const ACTION_ATTRIBUTE = 'equip/adr:action';

    private ResponseInterface $response;

    public function __construct(Resolver $resolver, ResponseInterface $response)
    {
        $this->resolver = $resolver;
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $action = $request->getAttribute(self::ACTION_ATTRIBUTE);
        $request = $request->withoutAttribute(self::ACTION_ATTRIBUTE);

        if (is_string($action)) {
            $action = $this->resolve($action);
        }

        $response = $this->invoke($action, $request, $this->response);

        $handler->handle($request);

        return $response;
    }

    /**
     * Invoke the action to prepare the response.
     *
     */
    private function invoke(
        ActionInterface $action,
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        return $action($request, $response);
    }
}
