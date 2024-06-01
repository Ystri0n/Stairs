<?php

declare(strict_types=1);

namespace Stairs\Tests\Router;

use DI\Container;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Stairs\Router\Exceptions\MethodNotAllowed;
use Stairs\Router\Exceptions\NotFound;
use Stairs\Router\Router;
use Stairs\Tests\Utils\DummyController;

#[CoversClass(Router::class)]
final class RouterTest extends TestCase
{
    protected static Router $router;

    public static function setUpBeforeClass(): void
    {
        self::$router = new Router(new Container(), [
            DummyController::class,
        ]);
    }

    public function testResolveStaticRoute(): void
    {
        [$callback, $parameters] = self::$router->resolve('GET', '/');

        self::assertSame($parameters, []);
    }

    public function testResolveDynamicRouteWithOptionalParameter(): void
    {
        [$callback, $parameters] = self::$router->resolve('GET', '/users/Ystri0n');

        self::assertSame($parameters, ['username' => 'Ystri0n']);
    }

    public function testResolveDynamicRouteWithoutOptionalParameter(): void
    {
        [$callback, $parameters] = self::$router->resolve('GET', '/users');

        self::assertSame($parameters, []);
    }

    public function testResolveNotFound(): void
    {
        self::expectException(NotFound::class);

        self::$router->resolve('GET', '/idk');
    }

    public function testResolveMethodNotAllowed(): void
    {
        self::expectException(MethodNotAllowed::class);

        self::$router->resolve('POST', '/');
    }

    public function testGenerateStaticRoute(): void
    {
        self::assertSame('/', self::$router->generate('homepage'));
    }

    public function testGenerateDynamicRoute(): void
    {
        self::assertSame('/users', self::$router->generate('users'));
        self::assertSame('/users/Ystri0n', self::$router->generate('users', ['username' => 'Ystri0n']));
    }
}
