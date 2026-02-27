<?php

declare(strict_types=1);

namespace App\CRM\Tests\Unit\Infrastructure\Client;

use App\Common\Core\Enum\AuthProvider;
use App\Contract\CRM\AccountClient\LoginLinkInterface;
use App\Contract\CRM\AccountClient\UserInterface;
use App\Contract\CRM\Exception\CrmClientException;
use App\CRM\Core\Exception\CrmClientInvalidResponseException;
use App\CRM\Core\Exception\CrmClientUnexpectedStatusCodeException;
use App\CRM\Infrastructure\Client\HttpCrmAccountClient;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

#[CoversClass(HttpCrmAccountClient::class)]
final class HttpCrmAccountClientTest extends TestCase
{
    private HttpClientInterface&MockObject $httpClient;
    private LoggerInterface&MockObject $logger;
    private HttpCrmAccountClient $client;

    private const string MARKET = 'de';

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->client = new HttpCrmAccountClient(
            $this->httpClient,
            self::MARKET,
            $this->logger
        );
    }

    public function testGetUserDataByEmailReturnsUserWhenFound(): void
    {
        // Given
        $email = 'test@example.com';
        $responseData = [
            'uref' => 'user-123',
            'email' => $email,
            'ssoExternalIdentifier' => 'ext-456',
            'ssoAuthProvider' => 'bistro_portal',
        ];

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(Response::HTTP_OK);
        $response->method('toArray')->willReturn($responseData);

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with(
                'GET',
                '/api/b2b-marketplace/sso/user/data',
                self::callback(function (array $options) use ($email) {
                    return $options['query']['field'] === 'email'
                        && $options['query']['value'] === $email
                        && $options['query']['_market'] === self::MARKET;
                })
            )
            ->willReturn($response);

        // When
        $user = $this->client->getUserDataByEmail($email);

        // Then
        self::assertInstanceOf(UserInterface::class, $user);
        self::assertSame('user-123', $user->getUref());
        self::assertSame($email, $user->getEmail());
        self::assertSame('ext-456', $user->getSsoExternalIdentifier());
        self::assertSame(AuthProvider::BistroPortal, $user->getSsoAuthProvider());
    }

    public function testGetUserDataByEmailReturnsNullWhenUserNotFound(): void
    {
        // Given
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(Response::HTTP_NOT_FOUND);

        $this->httpClient->method('request')->willReturn($response);

        // When
        $user = $this->client->getUserDataByEmail('notfound@example.com');

        // Then
        self::assertNull($user);
    }

    public function testGetUserDataByEmailReturnsUserWithoutSsoData(): void
    {
        // Given
        $email = 'test@example.com';
        $responseData = [
            'uref' => 'user-123',
            'email' => $email,
        ];

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(Response::HTTP_OK);
        $response->method('toArray')->willReturn($responseData);

        $this->httpClient->method('request')->willReturn($response);

        // When
        $user = $this->client->getUserDataByEmail($email);

        // Then
        self::assertInstanceOf(UserInterface::class, $user);
        self::assertNull($user->getSsoExternalIdentifier());
        self::assertNull($user->getSsoAuthProvider());
    }

    public function testGetUserDataBySsoReferenceReturnsUserWhenFound(): void
    {
        // Given
        $externalId = 'ext-789';
        $responseData = [
            'uref' => 'user-456',
            'email' => 'sso@example.com',
            'ssoExternalIdentifier' => $externalId,
            'ssoAuthProvider' => 'bistro_portal',
        ];

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(Response::HTTP_OK);
        $response->method('toArray')->willReturn($responseData);

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with(
                'GET',
                '/api/b2b-marketplace/sso/user/data',
                self::callback(function (array $options) use ($externalId) {
                    return $options['query']['field'] === 'sso'
                        && $options['query']['value'] === 'bistro_portal:' . $externalId
                        && $options['query']['_market'] === self::MARKET;
                })
            )
            ->willReturn($response);

        // When
        $user = $this->client->getUserDataBySsoReference('bistro_portal', $externalId);

        // Then
        self::assertInstanceOf(UserInterface::class, $user);
        self::assertSame('user-456', $user->getUref());
        self::assertSame($externalId, $user->getSsoExternalIdentifier());
    }

    public function testGetUserDataBySsoReferenceReturnsNullWhenNotFound(): void
    {
        // Given
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(Response::HTTP_NOT_FOUND);

        $this->httpClient->method('request')->willReturn($response);

        // When
        $user = $this->client->getUserDataBySsoReference('bistro_portal', 'unknown');

        // Then
        self::assertNull($user);
    }

    public function testGetUserDataByEmailThrowsExceptionWhenUnexpectedStatusCode(): void
    {
        // Given
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(Response::HTTP_INTERNAL_SERVER_ERROR);
        $response->method('toArray')->willReturn([]);

        $this->httpClient->method('request')->willReturn($response);

        // Expect
        $this->logger->expects(self::once())->method('error');

        $this->expectException(CrmClientUnexpectedStatusCodeException::class);

        // When
        $this->client->getUserDataByEmail('test@example.com');
    }

    public function testGetUserDataByEmailThrowsExceptionWhenMissingRequiredFields(): void
    {
        // Given
        $responseData = ['uref' => 'user-123']; // Missing email

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(Response::HTTP_OK);
        $response->method('toArray')->willReturn($responseData);

        $this->httpClient->method('request')->willReturn($response);

        // Expect
        $this->logger->expects(self::once())->method('error');

        $this->expectException(CrmClientInvalidResponseException::class);
        $this->expectExceptionMessage('Missing required fields: uref or email');

        // When
        $this->client->getUserDataByEmail('test@example.com');
    }

    public function testGetUserDataByEmailWrapsTransportExceptionsInCrmClientException(): void
    {
        // Given
        $transportException = new \Exception('Network error');

        $this->httpClient
            ->method('request')
            ->willThrowException($transportException);

        // Expect
        $this->logger->expects(self::once())->method('error');

        $this->expectException(CrmClientException::class);
        $this->expectExceptionMessage('Network error');

        // When
        $this->client->getUserDataByEmail('test@example.com');
    }

    public function testGetLoginLinkReturnsLoginLinkWhenFound(): void
    {
        // Given
        $uref = 'user-123';
        $linkUrl = 'https://login.example.com/token123';
        $responseData = ['link' => $linkUrl];

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(Response::HTTP_OK);
        $response->method('toArray')->willReturn($responseData);

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with(
                'GET',
                '/api/b2b-marketplace/sso/user/login-link',
                self::callback(function (array $options) use ($uref) {
                    return $options['query']['uref'] === $uref
                        && $options['query']['_market'] === self::MARKET;
                })
            )
            ->willReturn($response);

        // When
        $loginLink = $this->client->getLoginLink($uref);

        // Then
        self::assertInstanceOf(LoginLinkInterface::class, $loginLink);
        self::assertSame($linkUrl, $loginLink->getUrl());
    }

    public function testGetLoginLinkReturnsNullWhenUserNotFound(): void
    {
        // Given
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(Response::HTTP_NOT_FOUND);

        $this->httpClient->method('request')->willReturn($response);

        // When
        $loginLink = $this->client->getLoginLink('unknown-user');

        // Then
        self::assertNull($loginLink);
    }

    public function testGetLoginLinkThrowsExceptionWhenUnexpectedStatusCode(): void
    {
        // Given
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(Response::HTTP_FORBIDDEN);
        $response->method('toArray')->willReturn([]);

        $this->httpClient->method('request')->willReturn($response);

        // Expect
        $this->logger->expects(self::once())->method('error');

        $this->expectException(CrmClientUnexpectedStatusCodeException::class);

        // When
        $this->client->getLoginLink('user-123');
    }

    public function testGetLoginLinkThrowsExceptionWhenLinkFieldMissing(): void
    {
        // Given
        $responseData = ['other' => 'data'];

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(Response::HTTP_OK);
        $response->method('toArray')->willReturn($responseData);

        $this->httpClient->method('request')->willReturn($response);

        // Expect
        $this->logger->expects(self::once())->method('error');

        $this->expectException(CrmClientInvalidResponseException::class);
        $this->expectExceptionMessage('Missing or invalid "link" field in response');

        // When
        $this->client->getLoginLink('user-123');
    }

    public function testGetLoginLinkThrowsExceptionWhenLinkFieldNotString(): void
    {
        // Given
        $responseData = ['link' => 12345];

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(Response::HTTP_OK);
        $response->method('toArray')->willReturn($responseData);

        $this->httpClient->method('request')->willReturn($response);

        // Expect
        $this->logger->expects(self::once())->method('error');

        $this->expectException(CrmClientInvalidResponseException::class);

        // When
        $this->client->getLoginLink('user-123');
    }

    public function testGetLoginLinkWrapsTransportExceptionsInCrmClientException(): void
    {
        // Given
        $transportException = new \Exception('Connection timeout');

        $this->httpClient
            ->method('request')
            ->willThrowException($transportException);

        // Expect
        $this->logger->expects(self::once())->method('error');

        $this->expectException(CrmClientException::class);
        $this->expectExceptionMessage('Connection timeout');

        // When
        $this->client->getLoginLink('user-123');
    }
}
