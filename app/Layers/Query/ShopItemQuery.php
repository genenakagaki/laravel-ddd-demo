<?php

namespace App\Layers\Query;

use App\Layers\Persistence\Member\MemberData;
use App\Layers\Persistence\Shop\ShopItemData;

class ShopItemQuery
{
    public int $shopItemId;
    public string $itemName;
    public int $price;
    public int $inventoryCount;
    public int $sellerId;
    public string $sellerName;

    public function __construct(ShopItemData $shopItemData, MemberData $memberData)
    {
        $this->shopItemId = $shopItemData->shopItemId;
        $this->itemName = $shopItemData->itemName;
        $this->price = $shopItemData->price;
        $this->inventoryCount = $shopItemData->inventoryCount;
        $this->sellerId = $memberData->memberId;
        $this->sellerName = $memberData->username;
    }


}
