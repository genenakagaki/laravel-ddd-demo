<?php

namespace App\Layers\Query;

use App\Layers\Domain\Member\Value\MemberId;
use App\Layers\Domain\Shop\Value\ShopItem;
use App\Layers\Persistence\Database;
use App\Layers\Persistence\Member\MemberData;
use App\Layers\Persistence\Shop\ShopItemData;
use Illuminate\Support\Collection;

class ShopItemQueryService
{
    private Database $db;

    public function __construct()
    {
        $this->db = app(Database::class);
    }

    public function findAll(): Collection
    {
        return $this->db->shopItemList->map(function (ShopItemData $data) {
            return $this->mapToQuery($data);
        });
    }

    public function findById(int $shopItemId): ?ShopItemQuery
    {
        $data = $this->db->shopItemList->where('shopItemId', $shopItemId)->first();
        if ($data == null) {
            return null;
        }

        return $this->mapToQuery($data);
    }

    private function mapToQuery(ShopItemData $data): ShopItemQuery {
        $member = $this->findMember($data->sellerId);

        return new ShopItemQuery($data, $member);
    }

    private function findMember(int $memberId): ?MemberData {
        return $this->db->memberList->where('memberId', $memberId)->first();
    }


}
