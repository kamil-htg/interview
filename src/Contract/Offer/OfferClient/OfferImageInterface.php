<?php

declare(strict_types=1);

namespace App\Contract\Offer\OfferClient;

interface OfferImageInterface
{
    public function getId(): string;
    public function getExternalUrl(): string;
}
