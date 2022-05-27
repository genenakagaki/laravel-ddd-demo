<?php

namespace App\Layers\Domain\Shop\Type;

enum PurchaseStatusType
{
    case Started;
    case AwaitingItemShipping;
    case ItemShipped;
}
