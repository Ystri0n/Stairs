<?php

declare(strict_types=1);

namespace Stairs\Utils;

use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Http\Message\ResponseInterface;
use Stairs\Interfaces\ResponseEmitterInterface;

class ResponseEmitter implements ResponseEmitterInterface
{
    public function emit(ResponseInterface $response): void
    {
        (new SapiEmitter())->emit($response);
    }
}
