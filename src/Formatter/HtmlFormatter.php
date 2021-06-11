<?php

namespace Equip\Formatter;

use Equip\Adr\PayloadInterface;

abstract class HtmlFormatter implements FormatterInterface
{
    public static function accepts(): array
    {
        return ['text/html'];
    }

    public function type(): string
    {
        return 'text/html';
    }
}
