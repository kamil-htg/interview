<?php

declare(strict_types=1);

namespace App\Contract\CRM\AccountClient;

interface AccountClientInterface
{
    public function getUserByEmail(string $email): ?UserInterface;
}
