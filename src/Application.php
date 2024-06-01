<?php

declare(strict_types=1);

namespace Stairs;

use DI\Container;
use InvalidArgumentException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stairs\Dispatchers\QueueMiddlewareDispatcher;
use Stairs\Interfaces\MiddlewareDispatcherInterface;

class Application
{
    protected Container $container;

    protected MiddlewareDispatcherInterface $middlewareDispatcher;

    protected ServerRequestInterface $request;

    protected ResponseFactoryInterface $responseFactory;

    public function __construct(Container $container, ?MiddlewareDispatcherInterface $middlewareDispatcher = null)
    {
        $request = $container->get(ServerRequestInterface::class);

        if (!$request instanceof ServerRequestInterface) {
            throw new InvalidArgumentException('You must provide an instance of ServerRequestInterface.');
        }

        $responseFactory = $container->get(ResponseFactoryInterface::class);

        if (!$responseFactory instanceof ResponseFactoryInterface) {
            throw new InvalidArgumentException('You must provide an instance of ResponseFactoryInterface.');
        }

        $this->container = $container;
        $this->request = $request;
        $this->responseFactory = $responseFactory;
        $this->middlewareDispatcher = $middlewareDispatcher ?? new QueueMiddlewareDispatcher($responseFactory, $container);
    }

    public function getContainer(): Container
    {
        return $this->container;
    }

    public function getMiddlewareDispatcher(): MiddlewareDispatcherInterface
    {
        return $this->middlewareDispatcher;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function getResponseFactory(): ResponseFactoryInterface
    {
        return $this->responseFactory;
    }

    public function start(): void
    {
        $this->middlewareDispatcher->handle($this->request);
    }
}
