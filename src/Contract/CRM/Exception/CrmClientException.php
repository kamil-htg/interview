<?php

declare(strict_types=1);

namespace App\Contract\CRM\Exception;

abstract class CrmClientException extends CrmException
{
    protected string $messagePattern = '%s';

    public function __construct(
        string $reason,
        string $url,
        ?\Throwable $previous = null,
    ) {
        parent::__construct(
            message: sprintf($this->messagePattern . 'at %s', $reason, $url),
            previous: $previous
        );
    }
}
