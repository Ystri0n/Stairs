<?php

declare(strict_types=1);

namespace Stairs\Router\Twig;

use Psr\Http\Message\ServerRequestInterface;
use Stairs\Router\Router;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouterExtension extends AbstractExtension
{
    public function __construct(
        protected Router $router,
        protected ServerRequestInterface $request,
    ) {
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('absolute_url', $this->absoluteUrl(...)),
            new TwigFunction('route', $this->route(...)),
        ];
    }

    public function absoluteUrl(string $path, string $query = '', string $fragment = ''): string
    {
        return (string) $this->request->getUri()->withPath($path)->withQuery($query)->withFragment($fragment);
    }

    /**
     * @param array<string, string> $params
     */
    public function route(string $name, array $params = [], bool $absoluteUrl = false): string
    {
        $path = $this->router->generate($name, $params);

        if (!$absoluteUrl) {
            return $path;
        }

        return $this->absoluteUrl($path);
    }
}
