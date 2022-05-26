<?php

namespace App\Layers\Domain\Type;

enum PurchaseStatusType
{
    case Started;
    case AwaitingItemShipping;
    case ItemShipped;
}
