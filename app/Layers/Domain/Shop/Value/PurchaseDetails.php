<?php

namespace App\Layers\Domain\Shop\Value;

class PurchaseDetails
{
    public int $shopItemId;
    public int $itemPrice;
    public int $itemPurchaseCount;
    public SalesDetails $salesDetails;

    public function __construct(int $shopItemId, int $itemPrice, int $itemPurchaseCount, SalesDetails $salesDetails)
    {
        $this->shopItemId = $shopItemId;
        $this->itemPrice = $itemPrice;
        $this->itemPurchaseCount = $itemPurchaseCount;
        $this->salesDetails = $salesDetails;
    }
}
