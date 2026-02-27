<?php

declare(strict_types=1);

namespace App\Contract\Offer\OfferClient;

interface OfferTermInterface
{
    public function getLanguage(): string;
    public function getType(): string;
    public function getUrl(): string;
}
