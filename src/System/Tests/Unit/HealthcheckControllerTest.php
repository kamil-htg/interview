<?php

declare(strict_types=1);

namespace App\System\Tests\Unit;

use App\System\Interface\Http\Healthcheck\HealthcheckController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(HealthcheckController::class)]
class HealthcheckControllerTest extends TestCase
{
    public function testHealthcheckReturnsSuccessResponse(): void
    {
        $controller = new HealthcheckController();
        $response = $controller();

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals(['OK'], json_decode((string)$response->getContent(), true));
        self::assertEquals('application/json', $response->headers->get('Content-Type'));
    }
}
