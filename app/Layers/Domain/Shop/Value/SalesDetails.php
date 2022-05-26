<?php

namespace App\Layers\Domain\Shop\Value;

class SalesDetails
{
    public const SERVICE_FEE_PERCENTAGE = 0.1;

    public int $itemTotal;
    public int $serviceFee;
    public int $deliveryFee;
    public int $billedAmountToBuyer;

    public int $paymentToSeller;

    public function __construct(int $itemTotal, int $serviceFee, int $deliveryFee, int $billedAmountToBuyer, int $paymentToSeller)
    {
        $this->itemTotal = $itemTotal;
        $this->serviceFee = $serviceFee;
        $this->deliveryFee = $deliveryFee;
        $this->billedAmountToBuyer = $billedAmountToBuyer;
        $this->paymentToSeller = $paymentToSeller;
    }

    public static function create(int $itemPrice, int $itemPurchaseCount, int $deliveryFee): SalesDetails {
        $itemTotal = $itemPrice * $itemPurchaseCount;
        $serviceFee = $itemTotal * self::SERVICE_FEE_PERCENTAGE;
        $billedAmountToBuyer = $itemTotal + $serviceFee + $deliveryFee;
        $paymentToSeller = $itemTotal + $deliveryFee;
        return new SalesDetails($itemTotal, $serviceFee, $deliveryFee, $billedAmountToBuyer, $paymentToSeller);
    }
}
