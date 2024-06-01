<?php

declare(strict_types=1);

namespace Stairs\Tests\Router;

use DI\Container;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Stairs\Dispatchers\QueueMiddlewareDispatcher;
use Stairs\Interfaces\MiddlewareDispatcherInterface;
use Stairs\Router\Exceptions\MethodNotAllowed;
use Stairs\Router\Exceptions\NotFound;
use Stairs\Router\Router;
use Stairs\Router\RouterMiddleware;
use Stairs\Tests\Utils\DummyController;

#[CoversClass(RouterMiddleware::class)]
final class RouterMiddlewareTest extends TestCase
{
    protected static Psr17Factory $psr17;

    protected static MiddlewareDispatcherInterface $middlewareDispatcher;

    public static function setUpBeforeClass(): void
    {
        self::$psr17 = new Psr17Factory();
        self::$middlewareDispatcher = new QueueMiddlewareDispatcher(self::$psr17);

        self::$middlewareDispatcher->add(new RouterMiddleware(new Router(new Container(), [
            DummyController::class,
        ])));
    }

    public function testDynamicRouteFound(): void
    {
        $response = self::$middlewareDispatcher->handle(self::$psr17->createServerRequest('GET', '/'));

        self::assertSame('Welcome to my website!', (string) $response->getBody());
    }

    public function testDynamicRouteFoundWithOptionalParameter(): void
    {
        $response = self::$middlewareDispatcher->handle(self::$psr17->createServerRequest('GET', '/users/Ystri0n'));

        self::assertSame('Hello Ystri0n!', (string) $response->getBody());
    }

    public function testDynamicRouteFoundWithoutOptionalParameter(): void
    {
        $response = self::$middlewareDispatcher->handle(self::$psr17->createServerRequest('GET', '/users'));

        self::assertSame('Hello World!', (string) $response->getBody());
    }

    public function testNotFound(): void
    {
        self::expectException(NotFound::class);

        self::$middlewareDispatcher->handle(self::$psr17->createServerRequest('GET', '/idk'));
    }

    public function testMethodNotAllowed(): void
    {
        self::expectException(MethodNotAllowed::class);

        self::$middlewareDispatcher->handle(self::$psr17->createServerRequest('POST', '/'));
    }
}
