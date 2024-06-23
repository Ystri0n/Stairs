<?php

declare(strict_types=1);

namespace Stairs\Utils;

class Session
{
    protected bool $hasChanged = false;

    /** @var array<string, mixed> */
    protected array $data = [];

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $this->hasChanged = true;

        $this->data[$key] = $value;
    }

    public function del(string $key): void
    {
        if ($this->has($key)) {
            $this->hasChanged = true;

            unset($this->data[$key]);
        }
    }

    public function hasChanged(): bool
    {
        return $this->hasChanged;
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }
}
