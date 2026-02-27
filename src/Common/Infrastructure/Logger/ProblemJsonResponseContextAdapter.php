<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Logger;

use App\Common\Infrastructure\Http\Response\ProblemJsonResponse;
use Generator;
use Htg\Logging\Context\ContextAdapterInterface;
use Htg\Logging\Context\Ctx;
use Htg\Logging\Context\LevelOfDetail;

final class ProblemJsonResponseContextAdapter implements ContextAdapterInterface
{
    public static function getRootField(): string
    {
        return 'problem_json_response';
    }

    public function supports(object $object): bool
    {
        return $object instanceof ProblemJsonResponse;
    }

    public function describe(object $object, LevelOfDetail $level): Generator
    {
        /** @var ProblemJsonResponse $object */
        yield 'status' => $object->getStatusCode();

        yield Ctx::max($object->getPayload());
    }
}
