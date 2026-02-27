<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Email\ContentResolver;

interface ContentResolverRepositoryInterface
{
    public function getResolver(string $payload): ?ContentResolverInterface;
}
