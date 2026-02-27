<?php

declare(strict_types=1);

namespace App\Contract\Offer\OfferClient;

interface OfferClientInterface
{
    public function getById(string $id): ?OfferInterface;
}
