<?php

declare(strict_types=1);

namespace App\Contract\Offer\OfferClient;

interface OfferInterface
{
    public function getId(): string;
    public function getMainType(): OfferType;
    public function getName(): string;
    public function getProviderName(): string;
    public function getProviderId(): string;
    /** @return array<int, string> */
    public function getSiblingIds(): array;
    /** @return array<int, string> */
    public function getIncludedProviders(): array;
    /** @return array<int, OfferImageInterface> */
    public function getImages(): array;
    public function getAddress(): ?OfferAddressInterface;
    public function getGoogleAddress(): ?OfferAddressInterface;
    public function getGeoLocation(): ?OfferGeoLocationInterface;
    public function getTranslations(): OfferTranslationsInterface;
    public function getEnrichedData(): ?OfferEnrichedDataInterface;
}
