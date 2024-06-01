<?php

declare(strict_types=1);

namespace Stairs\Router\Exceptions;

class Forbidden extends AbstractHttpException
{
    protected int $httpStatusCode = 403;

    protected string $httpMessage = 'Forbidden';
}
