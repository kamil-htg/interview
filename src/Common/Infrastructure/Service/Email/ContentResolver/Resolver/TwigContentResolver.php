<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Email\ContentResolver\Resolver;

use App\Common\Core\Enum\Language;
use App\Common\Infrastructure\Service\Email\ContentResolver\ContentResolverInterface;
use Twig\Environment;

/**
 * @see \App\Common\Tests\Unit\Infrastructure\Service\Email\ContentResolver\Resolver\TwigContentResolverTest
 */
final readonly class TwigContentResolver implements ContentResolverInterface
{
    private const string PREFIX = 'twig://';

    private const string LANG_PLACEHOLDER = '{lang}';

    public function __construct(
        private Environment $twig,
    ) {
    }

    public function supports(string $payload): bool
    {
        return str_starts_with($payload, self::PREFIX);
    }

    public function resolve(string $payload, Language $language, array $parameters = []): string
    {
        $template = substr($payload, strlen(self::PREFIX));
        $template = str_replace(self::LANG_PLACEHOLDER, $language->value, $template);

        return $this->twig->render($template, $parameters);
    }
}
