<?php

declare(strict_types=1);

namespace Stairs\Mailer;

use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    public function __construct(
        protected Address $from,
        protected string $smtpHost,
        protected int $smtpPort = 465,
        protected string $smtpEncryption = 'tls',
        protected ?string $smtpUsername = null,
        protected ?string $smtpPassword = null,
    ) {
    }

    public function send(Email $email): void
    {
        $phpmailer = new PHPMailer(true);

        $phpmailer->setFrom($this->from->getAddress(), $this->from->getName() ?? '');

        $phpmailer->isSMTP();
        $phpmailer->Host = $this->smtpHost;
        $phpmailer->Port = $this->smtpPort;
        $phpmailer->SMTPSecure = $this->smtpEncryption;
        $phpmailer->SMTPAuth = $this->smtpUsername !== null && $this->smtpUsername !== '';
        $phpmailer->Username = $this->smtpUsername ?? '';
        $phpmailer->Password = $this->smtpPassword ?? '';

        foreach ($email->getTo() as $address) {
            $phpmailer->addAddress($address->getAddress(), $address->getName() ?? '');
        }

        $phpmailer->Subject = $email->getSubject();
        $phpmailer->Body = $email->getBody();

        $phpmailer->isHTML(true);

        $phpmailer->send();
    }
}
