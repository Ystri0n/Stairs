<?php

declare(strict_types=1);

namespace Stairs\Router\Twig;

use DI\Attribute\Inject;
use Psr\Http\Message\ServerRequestInterface;
use Stairs\Router\Router;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouterExtension extends AbstractExtension
{
    #[Inject]
    protected ServerRequestInterface $request;

    #[Inject]
    protected Router $router;

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('route', $this->route(...)),
        ];
    }

    /**
     * @param array<string, string> $params
     */
    public function route(string $name, array $params = [], bool $pathOnly = true): string
    {
        $path = $this->router->generate($name, $params);

        if ($pathOnly) {
            return $path;
        }

        return (string) $this->request->getUri()->withPath($path)->withQuery('')->withFragment('');
    }
}
