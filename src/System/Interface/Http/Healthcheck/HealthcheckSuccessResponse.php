<?php

declare(strict_types=1);

namespace App\System\Interface\Http\Healthcheck;

use Symfony\Component\HttpFoundation\JsonResponse;

class HealthcheckSuccessResponse extends JsonResponse
{
    public function __construct()
    {
        parent::__construct(['OK']);
    }
}
