<?php

declare(strict_types=1);

namespace App\Common\Core\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ApiValidationException extends \RuntimeException
{
    public function __construct(
        private readonly ConstraintViolationListInterface $violations,
        string $message = 'Validation failed',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
