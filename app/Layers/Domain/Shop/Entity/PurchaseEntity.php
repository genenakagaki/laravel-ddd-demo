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
            Log::alert("最低一つ商品を選択してください。");
            throw new RuntimeException("最低一つ商品を選択してください。");
        }

        if ($buyerPaymentAmount != $this->purchase->salesDetails->billedAmountToBuyer) {
            Log::alert("請求額と会員からの支払い額が一致しないです。");
            throw new RuntimeException("請求額と会員からの支払い額が一致しないです。");
        }

        if ($this->shopItemInventoryCount < $this->purchase->itemPurchaseCount) {
            Log::alert("在庫が足りません。");
            throw new RuntimeException("在庫が足りません。");
        }

        $this->shopItemInventoryCount -= $this->purchase->itemPurchaseCount;
        $this->status = PurchaseStatusType::AwaitingItemShipping;

        if ($this->shopItemInventoryCount == 0) {
            $this->addEvent(new ShopItemInventoryEmptyEvent($this->purchase->shopItemId));
        }
   }

    public function registerShipping(MemberId $registeredBy, MemberId $sellerId, string $trackingNumber) {
        if ($registeredBy !== $sellerId) {
            Log::alert("権限がありません。");
            throw new RuntimeException("権限がありません。");
        }

        if ($this->status !== PurchaseStatusType::AwaitingItemShipping) {
            Log::alert("郵送待ちではありません。");
            throw new RuntimeException("郵送待ちではありません。");
        }

        $this->shipping->trackingNumber = $trackingNumber;
        $this->status = PurchaseStatusType::ItemShipped;
    }
}
