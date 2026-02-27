<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Logger;

use App\Common\Core\Http\DTO\InvalidParam;
use App\Common\Interface\Http\Response\ProblemResponsePayload;
use Generator;
use Htg\Logging\Context\ContextAdapterInterface;
use Htg\Logging\Context\LevelOfDetail;

final class ProblemResponsePayloadContextAdapter implements ContextAdapterInterface
{
    public static function getRootField(): string
    {
        return 'problem_payload';
    }

    public function supports(object $object): bool
    {
        return $object instanceof ProblemResponsePayload;
    }

    public function describe(object $object, LevelOfDetail $level): Generator
    {
        /** @var ProblemResponsePayload $object */
        yield 'type' => $object->type;
        yield 'detail' => $object->detail;
        yield 'invalid_params' => array_map(fn (InvalidParam $param) => [
            'name' => $param->name,
            'type' => $param->type,
            'reason' => $param->reason,
        ], $object->invalidParams);
    }
}
