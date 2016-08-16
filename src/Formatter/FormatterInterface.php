<?php

namespace Equip\Formatter;

interface FormatterInterface
{
    /**
     * Get the content types this formatter can satisfy.
     */
    public static function accepts(): array;

    /**
     * Get the content type this formatter will generate.
     *
     */
    public function type(): string;

    /**
     * Get the formatted version of provided content.
     */
    public function format(mixed $content): string;
}
