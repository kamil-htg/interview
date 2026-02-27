<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Email\ContentResolver\Resolver;

use App\Common\Core\Enum\Language;
use App\Common\Infrastructure\Service\Email\ContentResolver\ContentResolverInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @see \App\Common\Tests\Unit\Infrastructure\Service\Email\ContentResolver\Resolver\TranslationsContentResolverTest
 */
final readonly class TranslationsContentResolver implements ContentResolverInterface
{
    private const string PREFIX = 'trans://';

    private const string DEFAULT_DOMAIN = 'email';

    public function __construct(
        private TranslatorInterface $translator,
    ) {
    }

    public function supports(string $payload): bool
    {
        return str_starts_with($payload, self::PREFIX);
    }

    public function resolve(string $payload, Language $language, array $parameters = []): string
    {
        $payload = substr($payload, strlen(self::PREFIX));

        if (str_contains($payload, '::')) {
            [$domain, $id] = explode('::', $payload, 2);
        } else {
            [$domain, $id] = [self::DEFAULT_DOMAIN, $payload];
        }

        return $this->translator->trans($id, $parameters, $domain, $language->value);
    }
}
