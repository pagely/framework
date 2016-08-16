<?php

namespace Equip\Formatter;

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

    public function format(mixed $content): string
    {
        return json_encode($content, $this->options());
    }

    protected function options(): int
    {
        return 0;
    }
}
