<?php

namespace Equip\Formatter;

use Equip\Adr\PayloadInterface;

interface FormatterInterface
{
    /**
     * Get the content types this formatter can satisfy.
     */
    public static function accepts(): array;

    /**
     * Get the content type of the response body.
     */
    public function type(): string;

    /**
     * Get the response body from the payload.
     */
    public function body(PayloadInterface $payload): string;
}
