<?php

declare(strict_types=1);

namespace App\Common\Core\DTO;

final readonly class Element
{
    public function __construct(
        public string $identifier,
        /** @var Item[] */
        public array $items,
        public bool $enabled = false,
    ) {
    }
}
