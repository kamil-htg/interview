<?php

declare(strict_types=1);

namespace App\Contract\Offer\OfferClient;

interface OfferAddressInterface
{
    public function getAddress(): ?string;
    public function getZip(): ?string;
    public function getCity(): ?string;
    public function getState(): ?string;
    public function getRegion(): ?string;
    public function getCountry(): ?string;
    public function getCountryCode(): ?string;
}
