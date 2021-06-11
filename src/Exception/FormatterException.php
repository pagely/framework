<?php

namespace Equip\Exception;

use Equip\Formatter\FormatterInterface;
use InvalidArgumentException;

class FormatterException extends InvalidArgumentException
{
    public static function invalidClass(string $spec): FormatterException
    {
        return new FormatterException(sprintf(
            'Formatter class `%s` must implement `%s`',
            $spec,
            FormatterInterface::class
        ));
    }

     public static function needsQuality(string $formatter): FormatterException
     {
         return new FormatterException(sprintf(
             'No quality have been set for the `%s` formatter',
             $formatter
         ));
     }
}
