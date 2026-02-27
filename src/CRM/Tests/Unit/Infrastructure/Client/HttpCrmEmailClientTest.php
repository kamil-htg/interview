<?php

declare(strict_types=1);

namespace App\CRM\Tests\Unit\Infrastructure\Client;

use App\Common\Core\Enum\Language;
use App\CRM\Core\DTO\Email;
use App\CRM\Core\Exception\CrmClientUnexpectedStatusCodeException;
use App\CRM\Infrastructure\Client\HttpCrmEmailClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @covers \App\CRM\Infrastructure\Client\HttpCrmEmailClient
 */
final class HttpCrmEmailClientTest extends TestCase
{
    private HttpClientInterface&MockObject $httpClient;
    private LoggerInterface&MockObject $logger;
    private HttpCrmEmailClient $client;

    private const string MARKET = 'de';
    private const string SENDER_ADDRESS = 'noreply@example.com';
    private const string SENDER_NAME = 'Test Sender';

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->client = new HttpCrmEmailClient(
            $this->httpClient,
            self::MARKET,
            self::SENDER_ADDRESS,
            self::SENDER_NAME,
            $this->logger
        );
    }

    public function testSendSuccessfullySendsEmail(): void
    {
        // Given
        $email = new Email(
            recipients: ['recipient@example.com'],
            subject: 'Test Subject',
            body: '<p>Test Body</p>',
            language: Language::EN
        );

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(Response::HTTP_OK);
        $response->method('toArray')->willReturn(['success' => true]);

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with(
                'POST',
                '/api/email/transactional/enqueue',
                self::callback(function (array $options) use ($email) {
                    return $options['json']['emailType'] === 'Custom'
                        && $options['json']['market'] === self::MARKET
                        && $options['json']['locale'] === \App\Common\Core\Enum\Locale::EnUs
                        && $options['json']['senderEmail'] === self::SENDER_ADDRESS
                        && $options['json']['senderName'] === self::SENDER_NAME
                        && $options['json']['subject'] === $email->getSubject()
                        && $options['json']['recipients'] === $email->getRecipients()
                        && $options['json']['templateVariables']['content'] === $email->getBody();
                })
            )
            ->willReturn($response);

        $this->logger->expects(self::once())->method('info');

        // When
        $this->client->send($email);
    }

    public function testSendThrowsExceptionWhenStatusCodeIsNotOk(): void
    {
        // Given
        $email = new Email(
            recipients: ['recipient@example.com'],
            subject: 'Test Subject',
            body: '<p>Test Body</p>',
            language: Language::EN
        );

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(Response::HTTP_INTERNAL_SERVER_ERROR);

        $this->httpClient->method('request')->willReturn($response);

        // Expect
        $this->expectException(CrmClientUnexpectedStatusCodeException::class);

        // When
        $this->client->send($email);
    }

    public function testSendLogsEmailWhenSuccessful(): void
    {
        // Given
        $email = new Email(
            recipients: ['recipient@example.com'],
            subject: 'Test Subject',
            body: '<p>Test Body</p>',
            language: Language::EN
        );

        $responseData = ['success' => true];

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(Response::HTTP_OK);
        $response->method('toArray')->willReturn($responseData);

        $this->httpClient->method('request')->willReturn($response);

        // Expect
        $this->logger
            ->expects(self::once())
            ->method('info')
            ->with(
                '[CRM] Email sent',
                self::callback(function (array $context) use ($email, $responseData) {
                    return $context['email'] === $email->toArray()
                        && $context['response'] === $responseData;
                })
            );

        // When
        $this->client->send($email);
    }

    public function testSendConvertsLanguageToLocale(): void
    {
        // Given
        $email = new Email(
            recipients: ['recipient@example.com'],
            subject: 'Test Subject',
            body: '<p>Test Body</p>',
            language: Language::DE
        );

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(Response::HTTP_OK);
        $response->method('toArray')->willReturn([]);

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with(
                self::anything(),
                self::anything(),
                self::callback(function (array $options) {
                    return $options['json']['locale'] === \App\Common\Core\Enum\Locale::DeDe;
                })
            )
            ->willReturn($response);

        // When
        $this->client->send($email);
    }
}
