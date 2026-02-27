<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Assert;

use App\Common\Core\Assert\AssertAdapterInterface;
use Webmozart\Assert\Assert as WebmozartAssert;

class WebmozartAssertAdapter extends WebmozartAssert implements AssertAdapterInterface
{
    public function __construct()
    {
    }
}
