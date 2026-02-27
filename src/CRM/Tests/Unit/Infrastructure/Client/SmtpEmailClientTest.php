<?php

declare(strict_types=1);

namespace App\CRM\Tests\Unit\Infrastructure\Client;

use App\Common\Core\Enum\Language;
use App\CRM\Core\DTO\Email;
use App\CRM\Infrastructure\Client\SmtpEmailClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MailerEmail;

/**
 * @covers \App\CRM\Infrastructure\Client\SmtpEmailClient
 */
final class SmtpEmailClientTest extends TestCase
{
    private MailerInterface&MockObject $mailer;
    private SmtpEmailClient $client;

    private const string SENDER_ADDRESS = 'noreply@example.com';
    private const string SENDER_NAME = 'Test Sender';

    protected function setUp(): void
    {
        $this->mailer = $this->createMock(MailerInterface::class);

        $this->client = new SmtpEmailClient(
            self::SENDER_ADDRESS,
            self::SENDER_NAME,
            $this->mailer
        );
    }

    public function testSendSuccessfullySendsEmailViaMail(): void
    {
        // Given
        $email = new Email(
            recipients: ['recipient@example.com'],
            subject: 'Test Subject',
            body: '<p>Test Body</p>',
            language: Language::EN
        );

        // Expect
        $this->mailer
            ->expects(self::once())
            ->method('send')
            ->with(self::callback(function (MailerEmail $mailerEmail) use ($email) {
                $from = $mailerEmail->getFrom();
                $to = $mailerEmail->getTo();

                return count($from) === 1
                    && $from[0]->getAddress() === self::SENDER_ADDRESS
                    && $from[0]->getName() === self::SENDER_NAME
                    && count($to) === 1
                    && $to[0]->getAddress() === $email->getRecipients()[0]
                    && $mailerEmail->getSubject() === $email->getSubject()
                    && $mailerEmail->getHtmlBody() === $email->getBody();
            }));

        // When
        $this->client->send($email);
    }

    public function testSendHandlesMultipleRecipients(): void
    {
        // Given
        $recipients = ['recipient1@example.com', 'recipient2@example.com', 'recipient3@example.com'];
        $email = new Email(
            recipients: $recipients,
            subject: 'Test Subject',
            body: '<p>Test Body</p>',
            language: Language::EN
        );

        // Expect
        $this->mailer
            ->expects(self::once())
            ->method('send')
            ->with(self::callback(function (MailerEmail $mailerEmail) use ($recipients) {
                $to = $mailerEmail->getTo();

                return count($to) === 3
                    && $to[0]->getAddress() === $recipients[0]
                    && $to[1]->getAddress() === $recipients[1]
                    && $to[2]->getAddress() === $recipients[2];
            }));

        // When
        $this->client->send($email);
    }

    public function testSendSetsCorrectSenderInformation(): void
    {
        // Given
        $email = new Email(
            recipients: ['recipient@example.com'],
            subject: 'Test Subject',
            body: '<p>Test Body</p>',
            language: Language::EN
        );

        // Expect
        $this->mailer
            ->expects(self::once())
            ->method('send')
            ->with(self::callback(function (MailerEmail $mailerEmail) {
                $from = $mailerEmail->getFrom();

                return count($from) === 1
                    && $from[0]->getAddress() === self::SENDER_ADDRESS
                    && $from[0]->getName() === self::SENDER_NAME;
            }));

        // When
        $this->client->send($email);
    }

    public function testSendSetsSubjectAndBody(): void
    {
        // Given
        $email = new Email(
            recipients: ['recipient@example.com'],
            subject: 'Important Subject',
            body: '<h1>Important Body</h1><p>Some content</p>',
            language: Language::DE
        );

        // Expect
        $this->mailer
            ->expects(self::once())
            ->method('send')
            ->with(self::callback(function (MailerEmail $mailerEmail) use ($email) {
                return $mailerEmail->getSubject() === $email->getSubject()
                    && $mailerEmail->getHtmlBody() === $email->getBody();
            }));

        // When
        $this->client->send($email);
    }
}
