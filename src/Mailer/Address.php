<?php

declare(strict_types=1);

namespace Stairs\Mailer;

use Stairs\Mailer\Exceptions\InvalidAddressException;

class Address
{
    public function __construct(
        protected string $address,
        protected ?string $name = null,
    ) {
        if (filter_var($address, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidAddressException('This email address is not valid.');
        }
    }

    public function __toString(): string
    {
        if ($this->name === null) {
            return $this->address;
        }

        return sprintf('"%s" <%s>', $this->name, $this->address);
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
