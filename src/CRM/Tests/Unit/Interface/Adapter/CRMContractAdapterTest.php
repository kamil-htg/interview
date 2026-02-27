<?php

declare(strict_types=1);

namespace App\CRM\Tests\Unit\Interface\Adapter;

use App\Common\Core\Enum\AuthProvider;
use App\Contract\CRM\AccountClient\LoginLinkInterface;
use App\Contract\CRM\AccountClient\UserInterface;
use App\CRM\Core\Client\CrmLoginLinkClientInterface;
use App\CRM\Core\Client\CrmUserDataClientInterface;
use App\CRM\Interface\Adapter\AccountClientInterfaceContractAdapter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AccountClientInterfaceContractAdapter::class)]
final class CRMContractAdapterTest extends TestCase
{
    public function testGetUserByEmailDelegatesToClient(): void
    {
        // Given
        $email = 'test@example.com';
        $expectedUser = $this->createMock(UserInterface::class);

        $mockUserDataClient = $this->createMock(CrmUserDataClientInterface::class);
        $mockUserDataClient
            ->expects($this->once())
            ->method('getUserDataByEmail')
            ->with($email)
            ->willReturn($expectedUser);

        $mockLoginLinkClient = $this->createMock(CrmLoginLinkClientInterface::class);

        $adapter = new AccountClientInterfaceContractAdapter($mockUserDataClient, $mockLoginLinkClient);

        // When
        $user = $adapter->getUserByEmail($email);

        // Then
        self::assertSame($expectedUser, $user);
    }

    public function testGetUserBySsoReferenceDelegatesToClient(): void
    {
        // Given
        $authProvider = AuthProvider::BistroPortal;
        $externalId = 'ext-123';
        $expectedUser = $this->createMock(UserInterface::class);

        $mockUserDataClient = $this->createMock(CrmUserDataClientInterface::class);
        $mockUserDataClient
            ->expects($this->once())
            ->method('getUserDataBySsoReference')
            ->with('bistro_portal', 'ext-123')
            ->willReturn($expectedUser);

        $mockLoginLinkClient = $this->createMock(CrmLoginLinkClientInterface::class);

        $adapter = new AccountClientInterfaceContractAdapter($mockUserDataClient, $mockLoginLinkClient);

        // When
        $user = $adapter->getUserBySsoReference($authProvider, $externalId);

        // Then
        self::assertSame($expectedUser, $user);
    }

    public function testGetLoginLinkByUrefDelegatesToClient(): void
    {
        // Given
        $uref = 'test-uref';
        $expectedLink = $this->createMock(LoginLinkInterface::class);

        $mockUserDataClient = $this->createMock(CrmUserDataClientInterface::class);

        $mockLoginLinkClient = $this->createMock(CrmLoginLinkClientInterface::class);
        $mockLoginLinkClient
            ->expects($this->once())
            ->method('getLoginLink')
            ->with($uref)
            ->willReturn($expectedLink);

        $adapter = new AccountClientInterfaceContractAdapter($mockUserDataClient, $mockLoginLinkClient);

        // When
        $link = $adapter->getLoginLinkByUref($uref);

        // Then
        self::assertSame($expectedLink, $link);
    }
}
