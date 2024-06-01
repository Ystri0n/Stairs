<?php

declare(strict_types=1);

namespace Stairs\Utils;

use DateTimeInterface;

class Cookie
{
    /**
     * @param 'None'|'Lax'|'Strict' $sameSite
     */
    public function __construct(
        protected string $path = '/',
        protected string $domain = '',
        protected bool $secure = false,
        protected bool $httpOnly = false,
        protected string $sameSite = 'None',
    ) {
    }

    public function has(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

    public function get(string $name): ?string
    {
        return $_COOKIE[$name] ?? null;
    }

    public function set(string $name, string $value, DateTimeInterface|int $expires): void
    {
        if ($expires instanceof DateTimeInterface) {
            $expires = (int) $expires->format('U');
        }

        setcookie($name, $value, [
            'expires' => $expires,
            'path' => $this->path,
            'domain' => $this->domain,
            'secure' => $this->secure,
            'httponly' => $this->httpOnly,
            'samesite' => $this->sameSite,
        ]);
    }

    public function del(string $name): void
    {
        $this->set($name, '', 1);
    }
}
