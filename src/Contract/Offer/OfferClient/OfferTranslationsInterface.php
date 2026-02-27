<?php

declare(strict_types=1);

namespace App\Contract\Offer\OfferClient;

use App\Common\Core\Enum\Language;

interface OfferTranslationsInterface
{
    public function getNameTranslated(Language $language): ?string;
    public function getObjectNameTranslated(Language $language): ?string;
    public function getDescriptionTranslated(Language $language): ?string;
    public function getObjectDescriptionTranslated(Language $language): ?string;
    public function getHouseRulesDescriptionTranslated(Language $language): ?string;
    public function getCheckInInstructionsTranslated(Language $language): ?string;
    public function getSpecialCheckInInstructionsTranslated(Language $language): ?string;
    public function getArrivalDescriptionTranslated(Language $language): ?string;
}
