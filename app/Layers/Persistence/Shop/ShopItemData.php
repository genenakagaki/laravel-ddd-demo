<?php

namespace App\Layers\Repository\Shop\Data;

class ShopItemData
{
    public int $shopItemId;
    public int $sellerId;
    public int $price;
    public int $inventoryCount;

    /**
     * @param int $shopItemId
     * @param int $sellerId
     * @param int $price
     * @param int $inventoryCount
     */
    public function __construct(int $shopItemId, int $sellerId, int $price, int $inventoryCount)
    {
        $this->shopItemId = $shopItemId;
        $this->sellerId = $sellerId;
        $this->price = $price;
        $this->inventoryCount = $inventoryCount;
    }


}
