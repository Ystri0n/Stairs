<?php

declare(strict_types=1);

namespace Stairs\Router\Exceptions;

use Exception;
use Throwable;

abstract class AbstractHttpException extends Exception
{
    protected int $httpStatusCode;

    protected string $httpMessage;

    public function __construct(?string $message = null, ?int $statusCode = null, ?Throwable $previous = null)
    {
        parent::__construct($message ?? $this->httpMessage, $statusCode ?? $this->httpStatusCode, $previous);
    }
}
