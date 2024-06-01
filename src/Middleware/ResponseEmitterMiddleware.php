<?php

declare(strict_types=1);

namespace Stairs\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Stairs\Interfaces\ResponseEmitterInterface;

class ResponseEmitterMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected ResponseEmitterInterface $responseEmitter,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        $this->responseEmitter->emit($response);

        return $response;
    }
}
