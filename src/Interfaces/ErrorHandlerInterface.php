<?php

declare(strict_types=1);

namespace Stairs\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Throwable;

interface ErrorHandlerInterface
{
    public function handle(Throwable $t): ResponseInterface;
}
