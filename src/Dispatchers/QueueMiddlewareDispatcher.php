<?php

declare(strict_types=1);

namespace Stairs\Dispatchers;

use Ds\Queue;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

class QueueMiddlewareDispatcher extends AbstractMiddlewareDispatcher
{
    /** @var Queue<MiddlewareInterface>|null */
    protected ?Queue $queue = null;

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->queue === null) {
            $this->queue = new Queue($this->middleware);
        }

        if ($this->queue->isEmpty()) {
            $this->queue = null;

            return $this->responseFactory->createResponse();
        }

        return $this->queue->pop()->process($request, $this);
    }
}
