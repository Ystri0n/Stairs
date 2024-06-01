<?php

declare(strict_types=1);

namespace Stairs\Utils;

class Flashes
{
    public function __construct(
        protected Session $session,
    ) {
    }

    public function has(string $key): bool
    {
        return self::get($key, pop: false) !== [];
    }

    /**
     * @return array<array<string, mixed>>|array<string, mixed>
     */
    public function get(?string $key = null, bool $pop = true): array
    {
        $flashes = $this->session->get('_flashes');

        if (!is_array($flashes)) {
            return [];
        }

        if ($key !== null) {
            $flashes = $flashes[$key] ?? [];
        }

        if ($pop) {
            $this->del($key);
        }

        return $flashes;
    }

    public function add(string $key, mixed $value): void
    {
        $flashes = $this->session->get('_flashes');

        if (!is_array($flashes)) {
            $flashes = [];
        }

        if (!isset($flashes[$key])) {
            $flashes[$key] = [];
        }

        $flashes[$key][] = $value;

        $this->session->set('_flashes', $flashes);
    }

    public function del(?string $key = null): void
    {
        if ($key === null) {
            $this->session->del('_flashes');
            return;
        }

        $flashes = $this->session->get('_flashes');

        if (is_array($flashes) && isset($flashes[$key])) {
            unset($flashes[$key]);

            $this->session->set('_flashes', $flashes);
        }
    }
}
