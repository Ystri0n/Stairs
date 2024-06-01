<?php

declare(strict_types=1);

namespace Stairs\Router\Exceptions;

class NotFound extends AbstractHttpException
{
    protected int $httpStatusCode = 404;

    protected string $httpMessage = 'Not Found';
}
