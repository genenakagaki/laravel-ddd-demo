<?php

namespace Tests\Layers\Domain\Shop\Value;

use App\Layers\Domain\Shop\Value\PurchaseDetails;
use App\Layers\Domain\Shop\Value\ShippingDetails;
use PHPUnit\Framework\TestCase;

class PurchaseDetailsTest extends TestCase
{
    public function testCalculateBillingAmount()
    {

        $purchaseDetails = new PurchaseDetails(1, 100, 3,
            new ShippingDetails('from', 'to', 100));
        self::assertEquals($purchaseDetails->calculateBillingAmount(), 430);
    }

    public function testCalculatePaymentAmountToSeller() {
        $purchaseDetails = new PurchaseDetails(1, 100, 3,
            new ShippingDetails('from', 'to', 100));
        self::assertEquals($purchaseDetails->calculatePaymentAmountToSeller(), 400);
    }

}
