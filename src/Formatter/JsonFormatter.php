<?php

namespace Equip\Formatter;

use Equip\Adr\PayloadInterface;

class JsonFormatter implements FormatterInterface
{
    public static function accepts(): array
    {
        return ['application/json'];
    }

    public function type(): string
    {
        return 'application/json';
    }

    public function body(PayloadInterface $payload): string
    {
        return json_encode($payload->getOutput(), $this->options());
    }

    protected function options(): int
    {
        return 0;
    }
}
