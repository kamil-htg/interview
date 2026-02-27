<?php

declare(strict_types=1);

namespace App\CRM\Tests\Unit\Core\Client;

use App\Common\Core\Enum\Language;
use App\CRM\Core\Client\InMemoryCrmEmailClient;
use App\CRM\Core\DTO\Email;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(InMemoryCrmEmailClient::class)]
final class InMemoryCrmEmailClientTest extends TestCase
{
    private InMemoryCrmEmailClient $emailClient;

    protected function setUp(): void
    {
        $this->emailClient = new InMemoryCrmEmailClient();
    }

    public function testSendStoresEmail(): void
    {
        // Given
        $email = new Email(
            recipients: ['test@example.com'],
            subject: 'Test Subject',
            body: 'Test Body',
            language: Language::EN
        );

        // When
        $this->emailClient->send($email);

        // Then
        $sentEmails = $this->emailClient->getSentEmails();
        self::assertCount(1, $sentEmails);
        self::assertSame($email, $sentEmails[0]);
    }

    public function testGetSentEmailsReturnsEmptyArrayInitially(): void
    {
        // When
        $sentEmails = $this->emailClient->getSentEmails();

        // Then
        self::assertSame([], $sentEmails);
    }

    public function testClearMethodResetsAllEmails(): void
    {
        // Given
        $email = new Email(
            recipients: ['clear@example.com'],
            subject: 'Clear Test',
            body: 'Clear Body',
            language: Language::EN
        );
        $this->emailClient->send($email);

        // When
        $this->emailClient->clear();

        // Then
        self::assertSame([], $this->emailClient->getSentEmails());
    }

    public function testMultipleSendsStoreMultipleEmails(): void
    {
        // Given
        $email1 = new Email(
            recipients: ['user1@example.com'],
            subject: 'Subject 1',
            body: 'Body 1',
            language: Language::EN
        );
        $email2 = new Email(
            recipients: ['user2@example.com'],
            subject: 'Subject 2',
            body: 'Body 2',
            language: Language::DE
        );

        // When
        $this->emailClient->send($email1);
        $this->emailClient->send($email2);

        // Then
        $sentEmails = $this->emailClient->getSentEmails();
        self::assertCount(2, $sentEmails);
        self::assertSame($email1, $sentEmails[0]);
        self::assertSame($email2, $sentEmails[1]);
    }
}
