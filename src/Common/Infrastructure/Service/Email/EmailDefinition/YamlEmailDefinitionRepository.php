<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Email\EmailDefinition;

use App\Common\Core\Assert\Assert;
use App\Common\Core\Enum\EmailType;
use App\Common\Core\Enum\Language;
use Symfony\Component\Yaml\Yaml;

/**
 * @see \App\Common\Tests\Unit\Infrastructure\Service\Email\EmailDefinition\YamlEmailDefinitionRepositoryTest
 */
final readonly class YamlEmailDefinitionRepository implements EmailDefinitionRepositoryInterface
{
    /** @var array<string, EmailDefinition> */
    private array $data;

    public function __construct(string $yamlPath)
    {
        $this->data = $this->yamlToEmailDefinitions($yamlPath);
    }

    public function getByEmailType(EmailType $emailType): ?EmailDefinition
    {
        return $this->data[$emailType->value] ?? null;
    }

    /** @return array<string, EmailDefinition> */
    private function yamlToEmailDefinitions(string $yamlPath): array
    {
        $definitions = [];
        foreach (Yaml::parseFile($yamlPath) as $key => $value) {
            Assert::stringNotEmpty($key);

            Assert::isArray($value);
            Assert::keyExists($value, 'subject');
            Assert::keyExists($value, 'body');

            $languages = [];
            if (isset($value['languages'])) {
                Assert::isArray($value['languages']);

                foreach ($value['languages'] as $language) {
                    $languages[] = Language::from($language);
                }
            }

            if (isset($value['parameters'])) {
                Assert::isArray($value['parameters']);

                foreach ($value['parameters'] as $parameter) {
                    Assert::stringNotEmpty($parameter);
                }
            }

            $definitions[$key] = new EmailDefinition(
                subject: $value['subject'],
                body: $value['body'],
                languages: $languages,
                parameters: $value['parameters'] ?? [],
            );
        }

        return $definitions;
    }
}
