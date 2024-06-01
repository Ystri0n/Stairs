<?php

declare(strict_types=1);

namespace Stairs\Tests;

use DI\Container;
use Nyholm\Psr7Server\ServerRequestCreator;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Stairs\Application;
use Stairs\Middleware\ResponseEmitterMiddleware;
use Stairs\Router\Exceptions\NotFound;
use Stairs\Router\Router;
use Stairs\Router\RouterMiddleware;
use Stairs\Tests\Utils\DummyController;
use Stairs\Tests\Utils\DummyMiddleware;
use Stairs\Utils\ResponseEmitter;

#[CoversClass(Application::class)]
final class ApplicationTest extends TestCase
{
    protected static Container $container;

    public static function setUpBeforeClass(): void
    {
        self::$container = new Container([
            ServerRequestInterface::class => fn (Psr17Factory $psr17) => (new ServerRequestCreator($psr17, $psr17, $psr17, $psr17))->fromGlobals(),
            ResponseFactoryInterface::class => \DI\create(Psr17Factory::class),
        ]);
    }

    public function testBasicApplication(): void
    {
        self::expectOutputString('DCBA');

        $app = new Application(self::$container);

        $middlewareDispatcher = $app->getMiddlewareDispatcher();

        $middlewareDispatcher->add(new ResponseEmitterMiddleware(new ResponseEmitter()));
        $middlewareDispatcher->add(new DummyMiddleware('A'));
        $middlewareDispatcher->add(new DummyMiddleware('B'));
        $middlewareDispatcher->add(new DummyMiddleware('C'));
        $middlewareDispatcher->add(new DummyMiddleware('D'));

        $app->start();
    }

    public function testApplicationWithRouter(): void
    {
        self::expectException(NotFound::class);

        $app = new Application(self::$container);

        $middlewareDispatcher = $app->getMiddlewareDispatcher();

        $middlewareDispatcher->add(new RouterMiddleware(new Router(self::$container, [
            DummyController::class,
        ])));

        $app->start();
    }
}
