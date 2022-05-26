<?php

namespace App\Layers\Repository\Shop;

use App\Layers\Domain\Shop\Entity\PurchaseEntity;
use App\Layers\Domain\Shop\Value\PurchaseDetails;
use App\Layers\Persistence\Database;
use App\Layers\Persistence\Shop\PurchaseData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class PurchaseRepository
{
    private ShopItemRepository $shopItemRepository;
    private Database $db;

    public function __construct(ShopItemRepository $shopItemRepository)
    {
        $this->shopItemRepository = $shopItemRepository;
        $this->db = app(Database::class);
    }

    public function getNextId(): int
    {
        return $this->db->purchaseList->max('purchaseId') + 1;
    }

    public function findById(int $purchaseId): ?PurchaseEntity
    {
        $data = $this->db->purchaseList->where('purchaseId', $purchaseId)->first();
        if ($data == null) {
            return null;
        }

        $shopItem = $this->shopItemRepository->findById($data->purchaseDetails->shopItemId);

        return new PurchaseEntity($data->purchaseId,
            $data->buyerId,
            $data->purchaseDetails,
            $data->shippingDetails,
            $shopItem->inventoryCount,
            $data->status);
    }

    public function add(PurchaseEntity $entity): void
    {
        $this->shopItemRepository->updateInventory($entity->purchase->shopItemId, $entity->shopItemInventoryCount);

        $data = $this->db->purchaseList->add(
            new PurchaseData(
                $entity->purchaseId,
                $entity->buyerId,
                $entity->status,
                $entity->purchase,
                $entity->shipping
            ));
        $this->db->setPurchaseList($data);
    }

    public function save(PurchaseEntity $entity): void
    {
        $data = $this->db->purchaseList->map(function (PurchaseData $item) use ($entity) {
            if ($item->purchaseId == $entity->purchaseId) {

                // 在庫更新
                $this->shopItemRepository->updateInventory($entity->purchase->shopItemId, $entity->shopItemInventoryCount);

                // 購入履歴登録
                return new PurchaseData(
                    $entity->purchaseId,
                    $entity->buyerId,
                    $entity->status,
                    $entity->purchase,
                    $entity->shipping
                );
            }

            return $item;
        });

        $this->db->setPurchaseList($data);
    }
}
