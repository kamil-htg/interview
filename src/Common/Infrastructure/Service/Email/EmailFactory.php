<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Email;

use App\Common\Core\Enum\EmailType;
use App\Common\Core\Enum\Language;
use App\Common\Infrastructure\Service\Email\ContentResolver\ContentResolverInterface;
use App\Common\Infrastructure\Service\Email\ContentResolver\ContentResolverRepositoryInterface;
use App\Common\Infrastructure\Service\Email\EmailDefinition\EmailDefinition;
use App\Common\Infrastructure\Service\Email\EmailDefinition\EmailDefinitionRepositoryInterface;

final readonly class EmailFactory implements EmailFactoryInterface
{
    private const PARAMETER_KEY_LANGUAGE = 'language';

    public function __construct(
        private EmailDefinitionRepositoryInterface $emailRepository,
        private ContentResolverRepositoryInterface $contentResolverRepository,
    ) {
    }

    /**
     * @param string[] $recipients
     * @param array<string, mixed> $parameters
     *
     * @throws \InvalidArgumentException
     */
    public function build(EmailType $type, array $recipients, Language $language, array $parameters = []): Email
    {
        $definition = $this->getDefinition($type);
        $parameters = $this->enrichParameters($parameters, $language);

        $this->validateLanguages($type, $definition->getLanguages(), $language);
        $this->validateParameters($definition->getParameters(), $parameters);

        $subjectResolver = $this->getContentResolver($definition->getSubject());
        $contentResolver = $this->getContentResolver($definition->getBody());

        return new Email(
            recipients: $recipients,
            subject: $subjectResolver->resolve($definition->getSubject(), $language, $parameters),
            body: $contentResolver->resolve($definition->getBody(), $language, $parameters),
            language: $language
        );
    }

    /**
     * @param array<string, mixed> $parameters
     * @return array<string, mixed>
     */
    private function enrichParameters(array $parameters, Language $language): array
    {
        return array_merge($parameters, [
            self::PARAMETER_KEY_LANGUAGE => $language->value,
        ]);
    }

    private function getDefinition(EmailType $type): EmailDefinition
    {
        return $this->emailRepository->getByEmailType($type)
            ?? throw new \InvalidArgumentException('Email definition not found for type: ' . $type->value);
    }

    /** @param Language[] $languages */
    private function validateLanguages(EmailType $type, array $languages, Language $language): void
    {
        if (0 === count($languages)) {
            return; // no languages defined, all are supported
        }

        if (!in_array($language, $languages, true)) {
            throw new \InvalidArgumentException(
                sprintf('Language "%s" is not supported by "%s" email', $language->value, $type->value)
            );
        }
    }

    /**
     * @param string[] $requiredParameters
     * @param array<string, mixed> $providedParameters
     */
    private function validateParameters(array $requiredParameters, array $providedParameters): void
    {
        foreach ($requiredParameters as $requiredParameter) {
            if (false === array_key_exists($requiredParameter, $providedParameters)) {
                throw new \InvalidArgumentException('Missing required parameter: ' . $requiredParameter);
            }
        }
    }

    private function getContentResolver(string $payload): ContentResolverInterface
    {
        return $this->contentResolverRepository->getResolver($payload)
            ?? throw new \InvalidArgumentException('Unable to find resolver for: ' . $payload);
    }
}
