<?php

declare(strict_types=1);

namespace Stairs\Tests;

use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Stairs\Dispatchers\QueueMiddlewareDispatcher;
use Stairs\Tests\Utils\DummyMiddleware;

#[CoversClass(QueueMiddlewareDispatcher::class)]
final class QueueMiddlewareDispatcherTest extends TestCase
{
    protected static Psr17Factory $psr17;

    protected static QueueMiddlewareDispatcher $middlewareDispatcher;

    public static function setUpBeforeClass(): void
    {
        self::$psr17 = new Psr17Factory();
        self::$middlewareDispatcher = new QueueMiddlewareDispatcher(self::$psr17);

        self::$middlewareDispatcher->add(new DummyMiddleware('A'));
        self::$middlewareDispatcher->add(new DummyMiddleware('B'));
        self::$middlewareDispatcher->add(new DummyMiddleware('C'));
        self::$middlewareDispatcher->add(new DummyMiddleware('D'));
    }

    public function testHandle(): void
    {
        $response = self::$middlewareDispatcher->handle(self::$psr17->createServerRequest('GET', '/'));

        self::assertSame('DCBA', (string) $response->getBody());
    }

    public function testMultipleHandle(): void
    {
        $response = self::$middlewareDispatcher->handle(self::$psr17->createServerRequest('GET', '/'));

        self::assertSame('DCBA', (string) $response->getBody());

        $response = self::$middlewareDispatcher->handle(self::$psr17->createServerRequest('GET', '/'));

        self::assertSame('DCBA', (string) $response->getBody());
    }
}
