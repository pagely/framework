<?php

namespace Equip\Handler;

use Equip\Contract\ActionInterface;
use Equip\Resolver\ResolverTrait;
use Equip\Resolver\Resolver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ActionHandler
{
    use ResolverTrait;

    const ACTION_ATTRIBUTE = 'equip/adr:action';

    public function __construct(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ) {
        $action = $request->getAttribute(self::ACTION_ATTRIBUTE);
        $request = $request->withoutAttribute(self::ACTION_ATTRIBUTE);

        if (is_string($action)) {
            $action = $this->resolve($action);
        }

        $response = $this->handle($action, $request, $response);

        return $next($request, $response);
    }

    /**
     * Invoke the action to prepare the response.
     *
     * @param ActionInterface $action
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    private function handle(
        ActionInterface $action,
        ServerRequestInterface $request,
        ResponseInterface $response
    ) {
        return $action($request, $response);
    }
}
