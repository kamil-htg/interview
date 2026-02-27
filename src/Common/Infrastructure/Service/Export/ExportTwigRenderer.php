<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Export;

use App\Common\Core\Attribute\Export\AsTemplatedRenderableContext;
use App\Common\Core\DTO\Export\ExportContext;
use App\Common\Core\DTO\Export\ExportRequest;
use App\Common\Core\Exception\Export\UnableToRenderExportException;
use App\Common\Core\Service\Export\ExportRendererInterface;
use App\Common\Core\Service\Export\ExportTemplateResolverInterface;
use Twig\Environment;

final readonly class ExportTwigRenderer implements ExportRendererInterface
{
    public function __construct(
        private Environment $twig,
        private ExportTemplateResolverInterface $templateResolver,
    ) {
    }

    public function supports(ExportContext $context): bool
    {
        return new \ReflectionClass($context)->getAttributes(AsTemplatedRenderableContext::class) !== [];
    }

    public function render(ExportRequest $request, ExportContext $context): string
    {
        try {
            return $this->twig->render(
                $this->normalizeTemplatePath($this->templateResolver->resolve($request, $context)),
                [
                    'request' => $request,
                    'context' => $context,
                ]
            );
        } catch (\Throwable $e) {
            throw new UnableToRenderExportException(
                'An error occurred while rendering the export template.',
                previous: $e
            );
        }
    }

    private function normalizeTemplatePath(string $path): string
    {
        $path = trim($path);
        if (str_starts_with($path, '@')) {
            return $path;
        }
        if (
            str_starts_with($path, DIRECTORY_SEPARATOR) ||
            str_starts_with($path, '/') ||
            str_starts_with($path, '\\')
        ) {
            return '@' . ltrim($path, '/\\');
        }

        return '@' . $path;
    }
}
