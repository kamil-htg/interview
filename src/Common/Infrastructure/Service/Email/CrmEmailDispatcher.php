<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Email;

use App\Common\Core\Enum\EmailType;
use App\Common\Core\Enum\Language;
use App\Common\Core\Service\Email\EmailDispatcherInterface;
use App\Contract\CRM\EmailClient\EmailClientInterface;

/**
 * @see \App\Common\Tests\Unit\Infrastructure\Service\Email\CrmEmailDispatcherTest
 */
final readonly class CrmEmailDispatcher implements EmailDispatcherInterface
{
    public function __construct(
        private EmailClientInterface $emailClient,
        private EmailFactoryInterface $emailFactory,
    ) {
    }

    public function send(EmailType $emailType, array $recipients, Language $language, array $parameters = []): void
    {
        $this->emailClient->send(
            $this->emailFactory->build(
                type: $emailType,
                recipients: $recipients,
                language: $language,
                parameters: $parameters,
            )
        );
    }
}
