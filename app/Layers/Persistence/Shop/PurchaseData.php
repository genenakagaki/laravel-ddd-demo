<?php

namespace App\Layers\Persistence\Shop;

use App\Layers\Domain\Member\Value\MemberId;
use App\Layers\Domain\Shop\Value\PurchaseDetails;
use App\Layers\Domain\Shop\Value\SalesDetails;
use App\Layers\Domain\Shop\Value\ShippingDetails;
use App\Layers\Domain\Type\PurchaseStatusType;

class PurchaseData
{
    public int $purchaseId;
    public MemberId $buyerId;
    public PurchaseStatusType $status;
    public PurchaseDetails $purchaseDetails;
    public ShippingDetails $shippingDetails;

    public function __construct(int $purchaseId, MemberId $buyerId, PurchaseStatusType $status, PurchaseDetails $purchaseDetails, ShippingDetails $shippingDetails)
    {
        $this->purchaseId = $purchaseId;
        $this->buyerId = $buyerId;
        $this->status = $status;
        $this->purchaseDetails = $purchaseDetails;
        $this->shippingDetails = $shippingDetails;
    }


}
