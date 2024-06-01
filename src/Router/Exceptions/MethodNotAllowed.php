<?php

declare(strict_types=1);

namespace Stairs\Router\Exceptions;

class MethodNotAllowed extends AbstractHttpException
{
    protected int $httpStatusCode = 405;

    protected string $httpMessage = 'Method Not Allowed';
}
