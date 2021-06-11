<?php

namespace Equip\Exception;

use Equip\Adr\ResponderInterface;
use InvalidArgumentException;

class ResponderException extends InvalidArgumentException
{
    public static function invalidClass(string $spec): ResponderException
    {
        return new ResponderException(sprintf(
            'Responder class `%s` must implement `%s`',
            $spec,
            ResponderInterface::class
        ));
    }
}
