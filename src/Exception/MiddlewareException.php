<?php

namespace Equip\Exception;

use DomainException;

class MiddlewareException extends DomainException
{
    public static function notValidMiddleware(mixed $spec): MiddlewareException
    {
        if (is_object($spec)) {
            $spec = get_class($spec);
        }

        return new MiddlewareException(sprintf(
            'Middleware `%s` is not a psr-15 middleware or compatable callable',
            $spec
        ));
    }
}
