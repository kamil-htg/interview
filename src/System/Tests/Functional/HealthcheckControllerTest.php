<?php

declare(strict_types=1);

namespace App\System\Tests\Functional;

use App\Common\Tests\Functional\WebFunctionalTestCase;
use App\System\Interface\Http\Healthcheck\HealthcheckController;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(HealthcheckController::class)]
final class HealthcheckControllerTest extends WebFunctionalTestCase
{
    public function testHealthcheckReturnsSuccessResponse(): void
    {
        // When
        $this->client->request('GET', '/healthcheck');

        // Then
        $this->assertJsonResponse(200, ['OK']);
    }
}
