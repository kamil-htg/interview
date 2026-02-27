<?php

declare(strict_types=1);

namespace App\Common\Core\Exception;

use App\Contract\SSO\Exception\SsoException;
use Symfony\Component\Uid\Ulid;

final class ResourceNotFoundException extends SsoException
{
    /**
     * @param class-string $resource
     */
    public function __construct(string $resource, Ulid|string $identifier, string $message = '')
    {
        try {
            $shortName = new \ReflectionClass($resource)->getShortName();
        } catch (\ReflectionException) {
            $shortName = $resource;
        }

        parent::__construct(
            sprintf(
                '%s with identifier %s was not found.%s',
                $shortName,
                $identifier,
                $message
            )
        );
    }
}
