<?php

namespace App\Layers\Persistence\Shop;

class ShopItemData
{
    public int $shopItemId;
    public int $sellerId;
    public string $itemName;
    public int $price;
    public int $inventoryCount;

    /**
     * @param int $shopItemId
     * @param int $sellerId
     * @param string $itemName
     * @param int $price
     * @param int $inventoryCount
     */
    public function __construct(int $shopItemId, int $sellerId, string $itemName, int $price, int $inventoryCount)
    {
        $this->shopItemId = $shopItemId;
        $this->sellerId = $sellerId;
        $this->itemName = $itemName;
        $this->price = $price;
        $this->inventoryCount = $inventoryCount;
    }


}
