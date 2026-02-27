<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Email;

use App\Common\Core\Enum\Language;
use App\Contract\CRM\EmailClient\EmailInterface;

final readonly class Email implements EmailInterface
{
    /** @param string[] $recipients */
    public function __construct(
        private array $recipients,
        private string $subject,
        private string $body,
        private Language $language,
    ) {
    }

    /** @return string[] */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }
}
