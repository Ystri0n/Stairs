<?php

declare(strict_types=1);

namespace Stairs\Mailer;

class Email
{
    /**
     * @param Address[] $to
     */
    public function __construct(
        protected array $to = [],
        protected string $subject = '',
        protected string $body = '',
    ) {
    }

    /**
     * @param Address|string|array<Address|string> $addresses
     */
    public function to(Address|string|array $addresses): self
    {
        if ($addresses instanceof Address) {
            $this->to[] = $addresses;
        } elseif (is_string($addresses)) {
            $this->to[] = new Address($addresses);
        } else {
            foreach ($addresses as $address) {
                $this->to($address);
            }
        }

        return $this;
    }

    /**
     * @return Address[]
     */
    public function getTo(): array
    {
        return $this->to;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function subject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function body(string $body): self
    {
        $this->body = $body;

        return $this;
    }
}
