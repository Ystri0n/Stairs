<?php

declare(strict_types=1);

namespace Stairs\Router;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use UnexpectedValueException;

class RouterMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected Router $router,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        [$callback, $params] = $this->router->resolve($request->getMethod(), $request->getUri()->getPath());

        $request = $request->withAttribute('router.route_resolved.callback', $callback);
        $request = $request->withAttribute('router.route_resolved.params', $params);

        $container = $this->router->getContainer();

        $container->set(ResponseInterface::class, $handler->handle($request));

        $response = $container->call($callback, $params);

        if (!$response instanceof ResponseInterface) {
            throw new UnexpectedValueException('Your method must return an instance of ResponseInterface.');
        }

        return $response;
    }
}
