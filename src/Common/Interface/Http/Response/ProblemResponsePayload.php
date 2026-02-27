<?php

declare(strict_types=1);

namespace App\Common\Interface\Http\Response;

use App\Common\Core\Http\DTO\InvalidParam;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProblemResponsePayload',
    required: ['type'],
    type: 'object'
)]
final class ProblemResponsePayload implements \JsonSerializable
{
    /**
     * @param InvalidParam[] $invalidParams
     */
    public function __construct(
        #[OA\Property(type: 'string', example: 'validation-error')]
        public string $type,
        #[OA\Property(type: 'string', example: 'Request validation failed')]
        public ?string $detail = null,
        #[OA\Property(
            type: 'array',
            items: new OA\Items(
                required: ['name', 'type'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'email'),
                    new OA\Property(property: 'type', type: 'string', example: 'notBlank'),
                    new OA\Property(property: 'reason', type: 'string', example: 'Email is required'),
                ],
                type: 'object'
            )
        )]
        public array $invalidParams = []
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $data = ['type' => $this->type];

        if ($this->detail !== null) {
            $data['detail'] = $this->detail;
        }

        if ($this->invalidParams !== []) {
            $data['invalid-params'] = array_map(
                static function (InvalidParam $param): array {
                    $result = ['name' => $param->name, 'type' => $param->type];
                    if ($param->reason !== null) {
                        $result['reason'] = $param->reason;
                    }
                    return $result;
                },
                $this->invalidParams
            );
        }

        return $data;
    }
}
