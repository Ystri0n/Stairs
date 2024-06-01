<?php

declare(strict_types=1);

namespace Stairs\Dispatchers;

use Ds\Stack;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

class StackMiddlewareDispatcher extends AbstractMiddlewareDispatcher
{
    /** @var Stack<MiddlewareInterface>|null */
    protected ?Stack $stack = null;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->stack === null) {
            $this->stack = new Stack($this->middleware);
        }

        if ($this->stack->isEmpty()) {
            $this->stack = null;

            return $this->responseFactory->createResponse();
        }

        return $this->stack->pop()->process($request, $this);
    }
}
