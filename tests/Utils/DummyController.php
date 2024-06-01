<?php

declare(strict_types=1);

namespace Stairs\Tests\Utils;

use Psr\Http\Message\ResponseInterface;
use Stairs\Router\Attributes\Route;

class DummyController
{
    #[Route('/')]
    public function homepage(ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write('Welcome to my website!');

        return $response;
    }

    #[Route('/users[/{username}]')]
    public function users(ResponseInterface $response, string $username = null): ResponseInterface
    {
        if ($username === null) {
            $response->getBody()->write('Hello World!');
        } else {
            $response->getBody()->write("Hello {$username}!");
        }

        return $response;
    }
}
