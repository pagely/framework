<?php

namespace Equip\Handler;

use Equip\Env;
use Equip\Structure\Dictionary;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\XmlResponseHandler;

class ExceptionHandlerPreferences extends Dictionary
{

    private bool $debug;

    public function __construct(array $data = [], Env $env)
    {
        $data += [
            'text/html' => PrettyPageHandler::class,
            'application/javascript' => JsonResponseHandler::class,
            'application/json' => JsonResponseHandler::class,
            'application/ld+json' => JsonResponseHandler::class,
            'application/vnd.api+json' => JsonResponseHandler::class,
            'application/vnd.geo+json' => JsonResponseHandler::class,
            'application/xml' => XmlResponseHandler::class,
            'application/atom+xml' => XmlResponseHandler::class,
            'application/rss+xml' => XmlResponseHandler::class,
            'text/plain' => PlainTextHandler::class,
        ]; // @codeCoverageIgnore

        $this->debug = (bool) $env->getValue('DEBUG_STACKTRACE', false);
        parent::__construct($data);
    }

    public function displayDebug(): bool
    {
        return $this->debug;
    }
}
