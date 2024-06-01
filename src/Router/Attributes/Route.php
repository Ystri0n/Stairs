<?php

declare(strict_types=1);

namespace Stairs\Router\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route
{
    /**
     * @param string[] $methods
     */
    public function __construct(
        public readonly string $path,
        public readonly ?string $name = null,
        public readonly array $methods = ['GET'],
    ) {
    }
}
