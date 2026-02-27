<?php

declare(strict_types=1);

namespace App\Common\Core\Clock;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(ClockInterface::class)]
final class Clock implements ClockInterface
{
    private static ?ClockInterface $innerClock = null;

    public static function use(ClockInterface $clock): void
    {
        self::$innerClock = $clock;
    }

    public static function get(): self
    {
        return new self();
    }

    public function now(): DateTimeImmutable
    {
        return self::$innerClock?->now() ?? new DateTimeImmutable();
    }
}
