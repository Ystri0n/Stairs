<?php

declare(strict_types=1);

namespace Stairs\Dispatchers;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Stairs\Interfaces\MiddlewareDispatcherInterface;

abstract class AbstractMiddlewareDispatcher implements MiddlewareDispatcherInterface
{
    /**
     * @var MiddlewareInterface[]
     */
    protected array $middleware = [];

    public function __construct(
        protected ResponseFactoryInterface $responseFactory,
        protected ?ContainerInterface $container = null,
    ) {
    }

    /**
     * @param array<MiddlewareInterface|string> $middleware
     */
    public function set(array $middleware): MiddlewareDispatcherInterface
    {
        foreach ($middleware as $m) {
            $this->add($m);
        }

        return $this;
    }

    /**
     * @param MiddlewareInterface|string $middleware
     */
    public function add(MiddlewareInterface|string $middleware): MiddlewareDispatcherInterface
    {
        if (is_string($middleware) && $this->container?->has($middleware) === true) {
            $middleware = $this->container->get($middleware);
        }

        if (!$middleware instanceof MiddlewareInterface) {
            throw new InvalidArgumentException('You must provide an instance of MiddlewareInterface.');
        }

        $this->middleware[] = $middleware;

        return $this;
    }
}
