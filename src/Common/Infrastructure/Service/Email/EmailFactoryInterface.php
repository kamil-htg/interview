<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Email;

use App\Common\Core\Enum\EmailType;
use App\Common\Core\Enum\Language;

interface EmailFactoryInterface
{
    /**
     * @param string[] $recipients
     * @param array<string, mixed> $parameters
     */
    public function build(EmailType $type, array $recipients, Language $language, array $parameters = []): Email;
}
