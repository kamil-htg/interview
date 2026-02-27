<?php

declare(strict_types=1);

namespace App\Contract\CRM\AccountClient;

use App\Common\Core\Enum\AuthProvider;

interface UserInterface
{
    public function getUref(): string;
    public function getEmail(): string;
    public function getSsoExternalIdentifier(): ?string;
    public function getSsoAuthProvider(): ?AuthProvider;
}
