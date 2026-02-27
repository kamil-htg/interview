<?php

declare(strict_types=1);

namespace App\System\Interface\Http\Healthcheck;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/healthcheck', name: 'healthcheck', methods: ['GET'])]
final class HealthcheckController
{
    public function __invoke(): Response
    {
        return new HealthcheckSuccessResponse();
    }
}
