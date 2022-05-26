<?php

namespace App\Layers\Domain\Shop\Event;

use App\Layers\Domain\DomainEvent;

class ShopItemInventoryEmptyEvent implements DomainEvent
{
    public int $shopItemId;

    public function __construct(int $shopItemId)
    {
        $this->shopItemId = $shopItemId;
    }
}
