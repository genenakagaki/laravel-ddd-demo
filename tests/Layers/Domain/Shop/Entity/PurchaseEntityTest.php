<?php

namespace Tests\Layers\Domain\Shop\Entity;

use App\Layers\Domain\Member\Value\MemberId;
use App\Layers\Domain\Shop\Entity\PurchaseEntity;
use App\Layers\Domain\Shop\Type\PurchaseStatusType;
use App\Layers\Domain\Shop\Value\PurchaseDetails;
use App\Layers\Domain\Shop\Value\SalesDetails;
use App\Layers\Domain\Shop\Value\ShippingDetails;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class PurchaseEntityTest extends TestCase
{
    private function createEntity(): PurchaseEntity
    {
        $salesDetails = SalesDetails::create(100, 3, 100);
        $purchaseDetails = new PurchaseDetails(1, 100, 3, $salesDetails);
        $shippingDetails = new ShippingDetails('from', 'to', null);
        return new PurchaseEntity(1,
            new MemberId(1002),
            $purchaseDetails,
            $shippingDetails,
            200,
            PurchaseStatusType::Started);
    }

    /**
     * @test
     */
    public function 購入＿購入する商品の個数が0の場合＿エラー()
    {
        $entity = $this->createEntity();
        $entity->purchase->itemPurchaseCount = 0;

        $this->expectException(RuntimeException::class);
        $buyerPaymentAmount = $entity->purchase->salesDetails->billedAmountToBuyer;
        $entity->purchase($buyerPaymentAmount);
    }

    /**
     * @test
     */
    public function 購入＿支払額が請求額と一致しない場合＿エラー()
    {
        $entity = $this->createEntity();

        $this->expectException(RuntimeException::class);
        $buyerPaymentAmount = $entity->purchase->salesDetails->billedAmountToBuyer - 1;
        $entity->purchase($buyerPaymentAmount);
    }


    /**
     * @test
     */
    public function 購入＿在庫が足りない場合＿エラー()
    {
        $entity = $this->createEntity();
        $entity->shopItemInventoryCount = 0;

        $this->expectException(RuntimeException::class);
        $buyerPaymentAmount = $entity->purchase->salesDetails->billedAmountToBuyer;
        $entity->purchase($buyerPaymentAmount);
    }
    /**
     * @test
     */
    public function 購入＿在庫が更新されること()
    {
        $entity = $this->createEntity();
        $entity->shopItemInventoryCount = 100;
        $entity->purchase->itemPurchaseCount = 10;

        $buyerPaymentAmount = $entity->purchase->salesDetails->billedAmountToBuyer;
        $entity->purchase($buyerPaymentAmount);

        self::assertEquals($entity->shopItemInventoryCount, 90);
    }
    /**
     * @test
     */
    public function 購入＿購入ステータスが郵送待ちになっていること()
    {
        $entity = $this->createEntity();

        $buyerPaymentAmount = $entity->purchase->salesDetails->billedAmountToBuyer;
        $entity->purchase($buyerPaymentAmount);

        self::assertEquals($entity->status, PurchaseStatusType::AwaitingItemShipping);
    }
}
