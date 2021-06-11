<?php

namespace Equip\Handler;

use Equip\Exception\HttpException;
use Exception;
use InvalidArgumentException;
use Monolog\Handler\HandlerInterface;
use Negotiation\Negotiator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Equip\Resolver\Resolver;
use Whoops\Run as Whoops;

class ExceptionHandler
{
    private Negotiator $negotiator;
    private ExceptionHandlerPreferences $preferences;
    private Resolver $resolver;
    private Whoops $whoops;
    private ?LoggerInterface $logger;

    public function __construct(
        ExceptionHandlerPreferences $preferences,
        Negotiator $negotiator,
        Resolver $resolver,
        Whoops $whoops,
        ?LoggerInterface $logger = null
    ) {
        $this->preferences = $preferences;
        $this->logger = $logger;
        $this->negotiator = $negotiator;
        $this->resolver = $resolver;
        $this->whoops = $whoops;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        try {
            return $next($request, $response);
        } catch (Exception $e) {
            if ($this->logger) {
                if ($e instanceof HttpException) {
                    $this->logger->debug($e->getMessage(), ['exception' => $e]);
                } else {
                    $this->logger->error($e->getMessage(), ['exception' => $e]);
                }
            }

            $type = $this->type($request);

            $response = $response->withHeader('Content-Type', $type);

            try {
                if (method_exists($e, 'getHttpStatus')) {
                    $code = $e->getHttpStatus();
                } else {
                    $code = $e->getCode();
                }
                $response = $response->withStatus($code);
            } catch (InvalidArgumentException $_) {
                // Exception did not contain a valid code
                $response = $response->withStatus(500);
            }

            if ($e instanceof HttpException) {
                $response = $e->withResponse($response);
            }

            if ($this->preferences->displayDebug()) {

                $handler = $this->handler($type);
                $this->whoops->pushHandler($handler);

                $body = $this->whoops->handleException($e);
                $response->getBody()->write($body);

                $this->whoops->popHandler();
            }

            return $response;
        }
    }

    /**
     * Determine the preferred content type for the current request
     *
     */
    private function type(ServerRequestInterface $request): string
    {
        $accept = $request->getHeaderLine('Accept');
        $priorities = $this->preferences->toArray();

        if (!empty($accept)) {
            $preferred = $this->negotiator->getBest($accept, array_keys($priorities));
        }

        if (!empty($preferred)) {
            return $preferred->getValue();
        }

        return key($priorities);
    }

    /**
     * Retrieve the handler to use for the given type
     *
     */
    private function handler(string $type): mixed
    {
        return call_user_func($this->resolver, $this->preferences[$type]);
    }
}
