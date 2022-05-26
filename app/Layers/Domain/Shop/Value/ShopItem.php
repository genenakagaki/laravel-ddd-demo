<?php

namespace App\Layers\Domain\Shop\Value;

use App\Layers\Domain\Member\Value\MemberId;

class ShopItem
{
    public int $price;
    public int $inventoryCount;
    public MemberId $sellerId;

    public function __construct(int $price, int $inventoryCount, MemberId $sellerId)
    {
        $this->price = $price;
        $this->inventoryCount = $inventoryCount;
        $this->sellerId = $sellerId;
    }
}
