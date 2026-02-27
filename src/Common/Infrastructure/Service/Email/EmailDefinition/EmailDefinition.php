<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Email\EmailDefinition;

use App\Common\Core\Enum\Language;

final readonly class EmailDefinition
{
    /**
     * @param Language[] $languages
     * @param string[] $parameters
     */
    public function __construct(
        private string $subject,
        private string $body,
        private array $languages,
        private array $parameters = [],
    ) {
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    /** @return Language[] */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /** @return string[] */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
