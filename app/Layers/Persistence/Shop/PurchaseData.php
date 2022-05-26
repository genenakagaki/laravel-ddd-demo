<?php

namespace App\Layers\Repository\Shop\Data;

class PurchaseData
{
    public int $purchaseId;
    public int $buyerId;
    public int $shopItemId;
    public int $itemPurchaseCount;
    public string $fromAddress;
    public string $toAddress;
    public int $deliveryFee;
    public int $billingAmount;
    public int $paymentAmountToSeller;

    /**
     * @param int $purchaseId
     * @param int $buyerId
     * @param int $shopItemId
     * @param int $itemPurchaseCount
     * @param string $fromAddress
     * @param string $toAddress
     * @param int $deliveryFee
     * @param int $billingAmount
     * @param int $paymentAmountToSeller
     */
    public function __construct(int $purchaseId, int $buyerId, int $shopItemId, int $itemPurchaseCount, int $deliveryFee, int $billingAmount, int $paymentAmountToSeller)
    {
        $this->purchaseId = $purchaseId;
        $this->buyerId = $buyerId;
        $this->shopItemId = $shopItemId;
        $this->itemPurchaseCount = $itemPurchaseCount;
        $this->fromAddress = '省略';
        $this->toAddress = '省略';
        $this->deliveryFee = $deliveryFee;
        $this->billingAmount = $billingAmount;
        $this->paymentAmountToSeller = $paymentAmountToSeller;
    }

}
