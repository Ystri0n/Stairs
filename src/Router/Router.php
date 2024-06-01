<?php

declare(strict_types=1);

namespace Stairs\Router;

use DI\Container;
use FastRoute\ConfigureRoutes;
use FastRoute\Dispatcher\Result\MethodNotAllowed;
use FastRoute\Dispatcher\Result\NotMatched;
use FastRoute\FastRoute;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionMethod;
use Stairs\Router\Attributes\Route;
use Stairs\Router\Exceptions\MethodNotAllowed as MethodNotAllowedException;
use Stairs\Router\Exceptions\NotFound;

class Router
{
    protected FastRoute $fastRoute;

    /** @var array{cacheEnabled: bool, cacheFile: string} */
    protected array $settings;

    /**
     * @param class-string[] $controllers
     * @param array{cacheEnabled?: bool, cacheFile?: string} $settings
     */
    public function __construct(
        private Container $container,
        array $controllers = [],
        array $settings = [],
    ) {
        $this->settings = array_merge([
            'cacheEnabled' => false,
            'cacheFile' => 'routes.cache',
        ], $settings);

        $this->loadControllers($controllers);

        if (!$this->settings['cacheEnabled']) {
            $this->fastRoute = $this->fastRoute->disableCache();
        }
    }

    /**
     * @param class-string[] $controllers
     */
    protected function loadControllers(array $controllers): void
    {
        $this->fastRoute = FastRoute::recommendedSettings(function (ConfigureRoutes $routes) use ($controllers): void {
            foreach ($controllers as $controller) {
                $methods = (new ReflectionClass($controller))->getMethods();

                foreach ($methods as $method) {
                    $attributes = (new ReflectionMethod($controller, $method->getName()))->getAttributes(Route::class);

                    foreach ($attributes as $attribute) {
                        $route = $attribute->newInstance();

                        $routes->addRoute($route->methods, $route->path, [$controller, $method->getName()], [
                            ConfigureRoutes::ROUTE_NAME => $route->name ?? $method->getName(),
                        ]);
                    }
                }
            }
        }, $this->settings['cacheFile']); // @phpstan-ignore-line
    }

    /**
     * @return array{callable, array<string, string>}
     */
    public function resolve(string $method, string $path): array
    {
        $routeResolved = $this->fastRoute->dispatcher()->dispatch($method, $path);

        if ($routeResolved instanceof NotMatched) {
            throw new NotFound();
        }

        if ($routeResolved instanceof MethodNotAllowed) {
            throw new MethodNotAllowedException();
        }

        if (!is_callable($routeResolved->handler, true)) {
            throw new InvalidArgumentException('The resolved route does not lead to a callable.');
        }

        return [$routeResolved->handler, $routeResolved->variables];
    }

    /**
     * @param array<string, string> $params
     */
    public function generate(string $name, array $params = []): string
    {
        return $this->fastRoute->uriGenerator()->forRoute($name, $params); // @phpstan-ignore-line
    }

    public function getContainer(): Container
    {
        return $this->container;
    }
}
