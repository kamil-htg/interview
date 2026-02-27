<?php

declare(strict_types=1);

namespace App\CRM\Tests\Unit\Core\Client;

use App\Common\Core\Enum\AuthProvider;
use App\CRM\Core\Client\InMemoryCrmAccountClient;
use App\CRM\Core\DTO\User;
use App\CRM\Core\Exception\CrmClientGenericException;
use App\CRM\Core\Exception\CrmClientInvalidResponseException;
use App\CRM\Core\Exception\CrmClientUnexpectedStatusCodeException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(InMemoryCrmAccountClient::class)]
final class InMemoryCrmClientTest extends TestCase
{
    private InMemoryCrmAccountClient $crmAccountClient;

    protected function setUp(): void
    {
        $this->crmAccountClient = new InMemoryCrmAccountClient();
    }

    public function testStoresAndRetrievesUserByEmail(): void
    {
        // Given
        $user = new User(
            uref: 'test-uref-123',
            email: 'test@example.com',
            ssoExternalIdentifier: 'ext-123',
            ssoAuthProvider: AuthProvider::BistroPortal,
        );

        $this->crmAccountClient->addUser($user);

        // When
        $retrievedUser = $this->crmAccountClient->getUserDataByEmail('test@example.com');

        // Then
        self::assertNotNull($retrievedUser);
        self::assertSame('test-uref-123', $retrievedUser->getUref());
        self::assertSame('test@example.com', $retrievedUser->getEmail());
    }

    public function testRetrievesUserBySsoReference(): void
    {
        // Given
        $user = new User(
            uref: 'test-uref-456',
            email: 'sso@example.com',
            ssoExternalIdentifier: 'ext-456',
            ssoAuthProvider: AuthProvider::BistroPortal,
        );

        $this->crmAccountClient->addUser($user);

        // When
        $retrievedUser = $this->crmAccountClient->getUserDataBySsoReference('bistro_portal', 'ext-456');

        // Then
        self::assertNotNull($retrievedUser);
        self::assertSame('test-uref-456', $retrievedUser->getUref());
        self::assertSame('sso@example.com', $retrievedUser->getEmail());
    }

    public function testReturnsNullForNonExistentUser(): void
    {
        // When
        $user = $this->crmAccountClient->getUserDataByEmail('nonexistent@example.com');

        // Then
        self::assertNull($user);
    }

    public function testStoresAndRetrievesLoginLink(): void
    {
        // Given
        $this->crmAccountClient->addLoginLink('test-uref-789', 'https://crm.example.com/login/token-123');

        // When
        $loginLink = $this->crmAccountClient->getLoginLink('test-uref-789');

        // Then
        self::assertNotNull($loginLink);
        self::assertSame('https://crm.example.com/login/token-123', $loginLink->getUrl());
    }

    public function testReturnsNullForNonExistentLoginLink(): void
    {
        // When
        $loginLink = $this->crmAccountClient->getLoginLink('nonexistent-uref');

        // Then
        self::assertNull($loginLink);
    }

    public function testClearMethodResetsAllData(): void
    {
        // Given
        $user = new User(
            uref: 'test-uref-clear',
            email: 'clear@example.com',
            ssoExternalIdentifier: null,
            ssoAuthProvider: null,
        );

        $this->crmAccountClient->addUser($user);
        $this->crmAccountClient->addLoginLink('test-uref-clear', 'https://example.com/login');

        // When
        $this->crmAccountClient->clear();

        // Then
        self::assertNull($this->crmAccountClient->getUserDataByEmail('clear@example.com'));
        self::assertNull($this->crmAccountClient->getLoginLink('test-uref-clear'));
    }

    public function testGetUserDataByEmailThrowsUnexpectedStatusCodeException(): void
    {
        // Given
        $exception = new CrmClientUnexpectedStatusCodeException(
            '500 Internal Server Error',
            'https://crm.example.com/api/users'
        );
        $this->crmAccountClient->addExpectedExceptionForGetUserByEmail($exception);

        // Expect
        $this->expectException(CrmClientUnexpectedStatusCodeException::class);

        // When
        $this->crmAccountClient->getUserDataByEmail('test@example.com');
    }

    public function testGetUserDataByEmailThrowsInvalidResponseException(): void
    {
        // Given
        $exception = new CrmClientInvalidResponseException(
            'Invalid JSON response',
            'https://crm.example.com/api/users'
        );
        $this->crmAccountClient->addExpectedExceptionForGetUserByEmail($exception);

        // Expect
        $this->expectException(CrmClientInvalidResponseException::class);

        // When
        $this->crmAccountClient->getUserDataByEmail('test@example.com');
    }

    public function testGetUserDataByEmailThrowsGenericException(): void
    {
        // Given
        $exception = new CrmClientGenericException(
            'Network timeout',
            'https://crm.example.com/api/users'
        );
        $this->crmAccountClient->addExpectedExceptionForGetUserByEmail($exception);

        // Expect
        $this->expectException(CrmClientGenericException::class);

        // When
        $this->crmAccountClient->getUserDataByEmail('test@example.com');
    }

    public function testGetUserDataBySsoReferenceThrowsUnexpectedStatusCodeException(): void
    {
        // Given
        $exception = new CrmClientUnexpectedStatusCodeException(
            '404 Not Found',
            'https://crm.example.com/api/sso-users'
        );
        $this->crmAccountClient->addExpectedExceptionForGetUserBySsoReference($exception);

        // Expect
        $this->expectException(CrmClientUnexpectedStatusCodeException::class);

        // When
        $this->crmAccountClient->getUserDataBySsoReference('bistro_portal', 'ext-123');
    }

    public function testGetUserDataBySsoReferenceThrowsInvalidResponseException(): void
    {
        // Given
        $exception = new CrmClientInvalidResponseException(
            'Malformed response',
            'https://crm.example.com/api/sso-users'
        );
        $this->crmAccountClient->addExpectedExceptionForGetUserBySsoReference($exception);

        // Expect
        $this->expectException(CrmClientInvalidResponseException::class);

        // When
        $this->crmAccountClient->getUserDataBySsoReference('bistro_portal', 'ext-123');
    }

    public function testGetUserDataBySsoReferenceThrowsGenericException(): void
    {
        // Given
        $exception = new CrmClientGenericException(
            'Connection refused',
            'https://crm.example.com/api/sso-users'
        );
        $this->crmAccountClient->addExpectedExceptionForGetUserBySsoReference($exception);

        // Expect
        $this->expectException(CrmClientGenericException::class);

        // When
        $this->crmAccountClient->getUserDataBySsoReference('bistro_portal', 'ext-123');
    }

    public function testGetLoginLinkThrowsUnexpectedStatusCodeException(): void
    {
        // Given
        $exception = new CrmClientUnexpectedStatusCodeException(
            '503 Service Unavailable',
            'https://crm.example.com/api/login-link'
        );
        $this->crmAccountClient->addExpectedExceptionForGetLoginLink($exception);

        // Expect
        $this->expectException(CrmClientUnexpectedStatusCodeException::class);

        // When
        $this->crmAccountClient->getLoginLink('test-uref');
    }

    public function testGetLoginLinkThrowsInvalidResponseException(): void
    {
        // Given
        $exception = new CrmClientInvalidResponseException(
            'Invalid link format',
            'https://crm.example.com/api/login-link'
        );
        $this->crmAccountClient->addExpectedExceptionForGetLoginLink($exception);

        // Expect
        $this->expectException(CrmClientInvalidResponseException::class);

        // When
        $this->crmAccountClient->getLoginLink('test-uref');
    }

    public function testGetLoginLinkThrowsGenericException(): void
    {
        // Given
        $exception = new CrmClientGenericException(
            'Authentication failed',
            'https://crm.example.com/api/login-link'
        );
        $this->crmAccountClient->addExpectedExceptionForGetLoginLink($exception);

        // Expect
        $this->expectException(CrmClientGenericException::class);

        // When
        $this->crmAccountClient->getLoginLink('test-uref');
    }

    public function testExceptionsAreConsumedInOrder(): void
    {
        // Given
        $exception1 = new CrmClientGenericException(
            'First exception',
            'https://crm.example.com/api/users'
        );
        $exception2 = new CrmClientGenericException(
            'Second exception',
            'https://crm.example.com/api/users'
        );

        $this->crmAccountClient->addExpectedExceptionForGetUserByEmail($exception1);
        $this->crmAccountClient->addExpectedExceptionForGetUserByEmail($exception2);

        // When/Then - First call throws first exception
        try {
            $this->crmAccountClient->getUserDataByEmail('test@example.com');
            self::fail('Expected exception was not thrown');
        } catch (CrmClientGenericException $e) {
            self::assertStringContainsString('First exception', $e->getMessage());
        }

        // When/Then - Second call throws second exception
        try {
            $this->crmAccountClient->getUserDataByEmail('test@example.com');
            self::fail('Expected exception was not thrown');
        } catch (CrmClientGenericException $e) {
            self::assertStringContainsString('Second exception', $e->getMessage());
        }

        // When/Then - Third call returns null (no more exceptions)
        $user = $this->crmAccountClient->getUserDataByEmail('nonexistent@example.com');
        self::assertNull($user);
    }

    public function testClearMethodResetsExceptions(): void
    {
        // Given
        $exception = new CrmClientGenericException(
            'Test exception',
            'https://crm.example.com/api'
        );
        $this->crmAccountClient->addExpectedExceptionForGetUserByEmail($exception);
        $this->crmAccountClient->addExpectedExceptionForGetUserBySsoReference($exception);
        $this->crmAccountClient->addExpectedExceptionForGetLoginLink($exception);

        // When
        $this->crmAccountClient->clear();

        // Then - No exceptions should be thrown
        self::assertNull($this->crmAccountClient->getUserDataByEmail('test@example.com'));
        self::assertNull($this->crmAccountClient->getUserDataBySsoReference('bistro_portal', 'ext-123'));
        self::assertNull($this->crmAccountClient->getLoginLink('test-uref'));
    }
}
