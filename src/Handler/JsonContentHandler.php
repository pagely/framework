<?php

namespace Equip\Handler;

use Equip\Exception\HttpException;
use Middlewares\JsonPayload as AbstractHandler;

class JsonContentHandler extends AbstractHandler
{
    public function __construct(bool $assoc = true, int $maxDepth = 512, int $options = 0)
    {
        $this->associative($assoc);
        $this->depth($maxDepth);
        $this->options($options);
    }
}
