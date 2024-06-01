<?php

declare(strict_types=1);

namespace Stairs\Middleware;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Stairs\Utils\Cookie;
use Stairs\Utils\Session;

class SessionMiddleware implements MiddlewareInterface
{
    protected Cookie $cookie;

    protected Session $session;

    protected CacheItemPoolInterface $cache;

    public function __construct(Cookie $cookie, Session $session, CacheItemPoolInterface $cache)
    {
        $this->cookie = $cookie;
        $this->session = $session;
        $this->cache = $cache;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $id = $this->cookie->get('session');

        if ($id === null || !$this->cache->hasItem($id)) {
            do {
                $id = bin2hex(random_bytes(16));
            } while ($this->cache->hasItem($id));

            $this->cookie->set('session', $id, 0);
        }

        $item = $this->cache->getItem($id);

        if ($item->isHit() && is_array($item->get())) {
            $this->session->setData($item->get());
        }

        $response = $handler->handle($request);

        $item->set($this->session->getData());

        $this->cache->save($item);

        return $response;
    }
}
