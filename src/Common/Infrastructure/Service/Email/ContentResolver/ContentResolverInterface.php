<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Email\ContentResolver;

use App\Common\Core\Enum\Language;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(ContentResolverInterface::class)]
interface ContentResolverInterface
{
    public function supports(string $payload): bool;

    /** @param array<string, mixed> $parameters */
    public function resolve(string $payload, Language $language, array $parameters = []): string;
}
