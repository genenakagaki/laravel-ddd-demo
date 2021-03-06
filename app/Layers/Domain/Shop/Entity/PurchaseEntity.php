<?php

namespace App\Layers\Domain\Shop\Entity;

use App\Layers\Domain\DomainEntity;
use App\Layers\Domain\Member\Value\MemberId;
use App\Layers\Domain\Shop\Event\ShopItemInventoryEmptyEvent;
use App\Layers\Domain\Shop\Type\PurchaseStatusType;
use App\Layers\Domain\Shop\Value\PaymentByMember;
use App\Layers\Domain\Shop\Value\PurchaseDetails;
use App\Layers\Domain\Shop\Value\SalesDetails;
use App\Layers\Domain\Shop\Value\ShippingDetails;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PurchaseEntity extends DomainEntity
{
    public int $purchaseId;
    public MemberId $buyerId;
    public PurchaseDetails $purchase;
    public ShippingDetails $shipping;
    public int $shopItemInventoryCount;
    public PurchaseStatusType $status;

    public function __construct(int $purchaseId, MemberId $buyerId, PurchaseDetails $purchase, ShippingDetails $shipping, int $shopItemInventoryCount, PurchaseStatusType $status)
    {
        parent::__construct();
        $this->purchaseId = $purchaseId;
        $this->buyerId = $buyerId;
        $this->purchase = $purchase;
        $this->shipping = $shipping;
        $this->shopItemInventoryCount = $shopItemInventoryCount;
        $this->status = $status;
    }

    public static function create(int             $purchaseId,
                                  MemberId        $buyerId,
                                  int             $shopItemId,
                                  int             $itemPrice,
                                  int             $itemPurchaseCount,
                                  int             $deliveryFee,
                                  ShippingDetails $shippingDetails,
                                  int             $shopItemInventoryCount): PurchaseEntity
    {
        $salesDetails = SalesDetails::create($itemPrice, $itemPurchaseCount, $deliveryFee);
        $purchaseDetails = new PurchaseDetails($shopItemId, $itemPrice, $itemPurchaseCount, $salesDetails);
        return new PurchaseEntity($purchaseId, $buyerId, $purchaseDetails, $shippingDetails, $shopItemInventoryCount, PurchaseStatusType::Started);
    }

    public function purchase(int $buyerPaymentAmount): void
    {
        if ($this->purchase->itemPurchaseCount < 1) {
            Log::alert("????????????????????????????????????????????????");
            throw new RuntimeException("????????????????????????????????????????????????");
        }

        if ($buyerPaymentAmount != $this->purchase->salesDetails->billedAmountToBuyer) {
            Log::alert("??????????????????????????????????????????????????????????????????");
            throw new RuntimeException("??????????????????????????????????????????????????????????????????");
        }

        if ($this->shopItemInventoryCount < $this->purchase->itemPurchaseCount) {
            Log::alert("???????????????????????????");
            throw new RuntimeException("???????????????????????????");
        }

        $this->shopItemInventoryCount -= $this->purchase->itemPurchaseCount;
        $this->status = PurchaseStatusType::AwaitingItemShipping;

        if ($this->shopItemInventoryCount == 0) {
            $this->addEvent(new ShopItemInventoryEmptyEvent($this->purchase->shopItemId));
        }
   }

    public function registerShipping(MemberId $registeredBy, MemberId $sellerId, string $trackingNumber) {
        if ($registeredBy !== $sellerId) {
            Log::alert("???????????????????????????");
            throw new RuntimeException("???????????????????????????");
        }

        if ($this->status !== PurchaseStatusType::AwaitingItemShipping) {
            Log::alert("????????????????????????????????????");
            throw new RuntimeException("????????????????????????????????????");
        }

        $this->shipping->trackingNumber = $trackingNumber;
        $this->status = PurchaseStatusType::ItemShipped;
    }
}
