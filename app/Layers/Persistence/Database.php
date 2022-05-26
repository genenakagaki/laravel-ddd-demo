<?php

namespace App\Layers\Persistence;

use App\Layers\Domain\Type\PurchaseStatusType;
use App\Layers\Persistence\Member\MemberData;
use App\Layers\Persistence\Shop\PurchaseData;
use App\Layers\Persistence\Shop\ShopItemData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class Database
{
    private static string $shopItemFile = "shopItemListData";
    private static string $memberFile = "memberListData";
    private static string $purchaseFile = "purchaseListData";

    /**
     * @var Collection<int, MemberData>
     */
    public Collection $memberList;

    public function setMemberList(Collection $data): void
    {
        $this->memberList = $data;
        $this->writeDataFile(self::$memberFile, $data);
    }

    /**
     * @var Collection<int, PurchaseData>
     */
    public Collection $purchaseList;

    public function setPurchaseList(Collection $data): void
    {
        $this->purchaseList = $data;
        $this->writeDataFile(self::$purchaseFile, $data);
    }

    /**
     * @var Collection<int, ShopItemData>
     */
    public Collection $shopItemList;

    public function setShopItemList(Collection $data): void
    {
        $this->shopItemList = $data;
        $this->writeDataFile(self::$shopItemFile, $data);
    }

    public function __construct()
    {
        Log::alert("created");
        $this->memberList = $this->readDataFile(self::$memberFile, Collection::make([
            new MemberData(1001, 'FluffyCloud', "buyer@ceres-inc.jp", "東京都世田谷区用賀4-10-1"),
            new MemberData(1002, 'UptownFunk333', "seller@ceres-inc.jp", "東京都世田谷区用賀4-10-1"),
            new MemberData(1003, 'Gene', "g-nakagaki@ceres-inc.jp", "東京都世田谷区用賀4-10-1"),
        ]));

        $this->purchaseList = $this->readDataFile(self::$purchaseFile, Collection::make([
        ]));

        $this->shopItemList = $this->readDataFile(self::$shopItemFile, Collection::make([
            new ShopItemData(1, 1001, 'Coffee', 100, 50),
            new ShopItemData(2, 1001, 'Giant Water Bottle', 1000, 50),
        ]));
    }

    private function readDataFile(string $fileName, Collection $default): ?Collection
    {
        if (file_exists($fileName)) {
            $file = fopen($fileName, "r");
            $data = unserialize(fread($file, filesize($fileName)));
            fclose($file);
            return $data;
        }

        return $default;
    }

    private function writeDataFile(string $fileName, Collection $data): void {
        $file = fopen($fileName, "w");
        fwrite($file, serialize($data));
        fclose($file);
    }
}
