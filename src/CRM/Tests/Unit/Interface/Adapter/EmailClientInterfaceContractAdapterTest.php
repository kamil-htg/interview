<?php

declare(strict_types=1);

namespace App\CRM\Tests\Unit\Interface\Adapter;

use App\Common\Core\Enum\Language;
use App\Contract\CRM\EmailClient\EmailInterface;
use App\CRM\Core\Client\CrmEmailClientInterface;
use App\CRM\Core\DTO\Email;
use App\CRM\Interface\Adapter\EmailClientInterfaceContractAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\CRM\Interface\Adapter\EmailClientInterfaceContractAdapter
 */
final class EmailClientInterfaceContractAdapterTest extends TestCase
{
    private CrmEmailClientInterface&MockObject $crmEmailClient;
    private EmailClientInterfaceContractAdapter $adapter;

    protected function setUp(): void
    {
        $this->crmEmailClient = $this->createMock(CrmEmailClientInterface::class);
        $this->adapter = new EmailClientInterfaceContractAdapter($this->crmEmailClient);
    }

    public function testSendConvertsContractEmailInterfaceToCrmEmail(): void
    {
        // Given
        $contractEmail = $this->createMock(EmailInterface::class);
        $contractEmail->method('getRecipients')->willReturn(['recipient@example.com']);
        $contractEmail->method('getSubject')->willReturn('Test Subject');
        $contractEmail->method('getBody')->willReturn('<p>Test Body</p>');
        $contractEmail->method('getLanguage')->willReturn(Language::EN);

        // Expect
        $this->crmEmailClient
            ->expects(self::once())
            ->method('send')
            ->with(self::callback(function (Email $email) use ($contractEmail) {
                return $email->getRecipients() === $contractEmail->getRecipients()
                    && $email->getSubject() === $contractEmail->getSubject()
                    && $email->getBody() === $contractEmail->getBody()
                    && $email->getLanguage() === $contractEmail->getLanguage();
            }));

        // When
        $this->adapter->send($contractEmail);
    }

    public function testSendHandlesMultipleRecipients(): void
    {
        // Given
        $recipients = ['recipient1@example.com', 'recipient2@example.com'];
        $contractEmail = $this->createMock(EmailInterface::class);
        $contractEmail->method('getRecipients')->willReturn($recipients);
        $contractEmail->method('getSubject')->willReturn('Test Subject');
        $contractEmail->method('getBody')->willReturn('<p>Test Body</p>');
        $contractEmail->method('getLanguage')->willReturn(Language::DE);

        // Expect
        $this->crmEmailClient
            ->expects(self::once())
            ->method('send')
            ->with(self::callback(function (Email $email) use ($recipients) {
                return $email->getRecipients() === $recipients;
            }));

        // When
        $this->adapter->send($contractEmail);
    }

    public function testSendPreservesAllEmailData(): void
    {
        // Given
        $contractEmail = $this->createMock(EmailInterface::class);
        $contractEmail->method('getRecipients')->willReturn(['test@example.com']);
        $contractEmail->method('getSubject')->willReturn('Specific Subject');
        $contractEmail->method('getBody')->willReturn('<h1>HTML Body</h1>');
        $contractEmail->method('getLanguage')->willReturn(Language::FR);

        // Expect
        $this->crmEmailClient
            ->expects(self::once())
            ->method('send')
            ->with(self::callback(function (Email $email) {
                return $email->getRecipients() === ['test@example.com']
                    && $email->getSubject() === 'Specific Subject'
                    && $email->getBody() === '<h1>HTML Body</h1>'
                    && $email->getLanguage() === Language::FR;
            }));

        // When
        $this->adapter->send($contractEmail);
    }
}
