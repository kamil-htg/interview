<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Email\ContentResolver\Resolver;

use App\Common\Core\Enum\Language;
use App\Common\Infrastructure\Service\Email\ContentResolver\ContentResolverInterface;

/**
 * @see \App\Common\Tests\Unit\Infrastructure\Service\Email\ContentResolver\Resolver\PlainTextContentResolverTest
 */
final readonly class PlainTextContentResolver implements ContentResolverInterface
{
    public function supports(string $payload): bool
    {
        return 0 === preg_match('/^\w+:\/\/.+$/', $payload); // no uri in the beginning
    }

    public function resolve(string $payload, Language $language, array $parameters = []): string
    {
        return $payload;
    }
}
