<?php

namespace App\Layers\Repository\Shop;

use App\Layers\Domain\Shop\Entity\PurchaseEntity;
use Illuminate\Support\Collection;

class PurchaseRepository
{
    public Collection $data;

    public function getNextId(): int {
        // TODO: Implement
    }

    public function findById(int $shopItemId): ?PurchaseEntity
    {
        // TOOD: Implement
    }

    public function save(PurchaseEntity $entity): void {
        // TOOD: Implement
    }
}
