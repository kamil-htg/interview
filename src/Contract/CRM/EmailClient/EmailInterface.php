<?php

declare(strict_types=1);

namespace App\Contract\CRM\EmailClient;

use App\Common\Core\Enum\Language;

interface EmailInterface
{
    /** @return string[] */
    public function getRecipients(): array;

    public function getSubject(): string;

    public function getBody(): string;

    public function getLanguage(): Language;
}
