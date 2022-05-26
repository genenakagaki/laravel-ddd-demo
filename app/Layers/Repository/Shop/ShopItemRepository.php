<?php

namespace App\Layers\Repository\Shop;

use App\Layers\Domain\Member\Value\MemberId;
use App\Layers\Domain\Shop\Value\ShopItem;
use App\Layers\Persistence\Database;
use App\Layers\Persistence\Shop\ShopItemData;
use Illuminate\Database\Events\StatementPrepared;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ShopItemRepository
{
    public Database $db;

    public function __construct()
    {
        $this->db = app(Database::class);
    }

    public function findById(int $shopItemId): ?ShopItem
    {
        $data = $this->db->shopItemList->where('shopItemId', $shopItemId)->first();
        if ($data == null) {
            return null;
        }

        return new ShopItem($data->price, $data->inventoryCount, new MemberId($data->sellerId));
    }

    public function updateInventory(int $shopItemId, int $inventoryCount): void
    {
        $data = $this->db->shopItemList->map(function (ShopItemData $item) use ($shopItemId, $inventoryCount) {
            if ($item->shopItemId == $shopItemId) {
                return new ShopItemData($shopItemId, $item->sellerId, $item->itemName, $item->price, $inventoryCount);
            }

            return $item;
        });
        $this->db->setShopItemList($data);
    }
}
