<?php

declare(strict_types=1);

namespace App\Contract\Offer\OfferClient;

use App\Common\Core\Enum\Language;

interface OfferEnrichedDataInterface
{
    public function getNameTranslated(Language $language): ?string;

    public function getGeneralTitleTranslated(Language $language): ?string;
}
