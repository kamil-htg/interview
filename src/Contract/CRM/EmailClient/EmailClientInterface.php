<?php

declare(strict_types=1);

namespace App\Contract\CRM\EmailClient;

interface EmailClientInterface
{
    public function send(EmailInterface $email): void;
}
