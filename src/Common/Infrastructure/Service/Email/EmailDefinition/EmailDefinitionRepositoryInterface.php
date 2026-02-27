<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Email\EmailDefinition;

use App\Common\Core\Enum\EmailType;

interface EmailDefinitionRepositoryInterface
{
    public function getByEmailType(EmailType $emailType): ?EmailDefinition;
}
