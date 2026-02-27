<?php

declare(strict_types=1);

namespace App\Common\Core\Service\Email;

use App\Common\Core\Enum\EmailType;
use App\Common\Core\Enum\Language;

interface EmailDispatcherInterface
{
    /**
     * @param string[] $recipients
     * @param array<string, mixed> $parameters
     */
    public function send(EmailType $emailType, array $recipients, Language $language, array $parameters = []): void;
}
