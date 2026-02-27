<?php

declare(strict_types=1);

namespace App\Offer\Core\Service;

use App\Contract\Offer\OfferClient\OfferInterface;

interface OfferClientInterface
{
    public function getById(string $id): ?OfferInterface;
}
