<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Email\ContentResolver;

use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
 * @see \App\Common\Tests\Unit\Infrastructure\Service\Email\ContentResolver\ContentResolverRepositoryTest
 */
final class ContentResolverRepository implements ContentResolverRepositoryInterface
{
    /** @param iterable<ContentResolverInterface> $contentResolvers */
    public function __construct(
        #[AutowireIterator(ContentResolverInterface::class)]
        private iterable $contentResolvers,
    ) {
    }

    public function getResolver(string $payload): ?ContentResolverInterface
    {
        foreach ($this->contentResolvers as $contentResolver) {
            if ($contentResolver->supports($payload)) {
                return $contentResolver;
            }
        }

        return null;
    }
}
