<?php

declare(strict_types=1);

namespace App\Contract\Offer\OfferClient;

interface OfferGeoLocationInterface
{
    public function getLatitude(): float;

    public function getLongitude(): float;
}
