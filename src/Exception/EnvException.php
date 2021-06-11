<?php

namespace Equip\Exception;

use InvalidArgumentException;

class EnvException extends InvalidArgumentException
{
    public static function invalidFile(string $path): EnvException
    {
        return new EnvException(sprintf(
            'Environment file `%s` does not exist or is not readable',
            $path
        ));
    }

    public static function detectionFailed(): EnvException
    {
        return new EnvException(
            'Unable to automatically detect the location of a .env file'
        );
    }
}
