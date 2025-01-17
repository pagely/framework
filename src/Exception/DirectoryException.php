<?php

namespace Equip\Exception;

use Equip\Contract\ActionInterface;
use InvalidArgumentException;

class DirectoryException extends InvalidArgumentException
{
    public static function invalidEntry(mixed $value): DirectoryException
    {
        if (is_object($value)) {
            $value = get_class($value);
        }

        return new DirectoryException(sprintf(
            'Directory entry `%s` is not an `%s` instance',
            $value,
            ActionInterface::class
        ));
    }
}
