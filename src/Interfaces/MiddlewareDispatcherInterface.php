<?php

declare(strict_types=1);

namespace Stairs\Interfaces;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface MiddlewareDispatcherInterface extends RequestHandlerInterface
{
    /**
     * @param array<MiddlewareInterface|string> $middleware
     */
    public function set(array $middleware): MiddlewareDispatcherInterface;

    public function add(MiddlewareInterface|string $middleware): MiddlewareDispatcherInterface;
}
