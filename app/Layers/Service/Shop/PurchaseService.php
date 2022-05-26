<?php

namespace App\Layers\Service\Shop;

use App\Layers\Domain\Member\Value\MemberId;
use App\Layers\Domain\Shop\Entity\PurchaseEntity;
use App\Layers\Domain\Shop\Event\ShopItemInventoryEmptyEvent;
use App\Layers\Domain\Shop\Value\PaymentByMember;
use App\Layers\Domain\Shop\Value\ShippingDetails;
use App\Layers\External\Shipping\ShippingService;
use App\Layers\Repository\Member\MemberRepository;
use App\Layers\Repository\Shop\PurchaseRepository;
use App\Layers\Repository\Shop\ShopItemRepository;
use App\Layers\Service\Member\MemberNotificationService;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PurchaseService
{
    private PurchaseRepository $purchaseRepository;
    private ShopItemRepository $shopItemRepository;
    private ShippingService $shippingService;
    private MemberRepository $memberRepository;
    private MemberNotificationService $memberNotificationService;

    public function __construct(PurchaseRepository $shopItemPurchaseRepository, ShopItemRepository $shopItemRepository, ShippingService $shippingService, MemberRepository $memberRepository, MemberNotificationService $memberNotificationService)
    {
        $this->purchaseRepository = $shopItemPurchaseRepository;
        $this->shopItemRepository = $shopItemRepository;
        $this->shippingService = $shippingService;
        $this->memberRepository = $memberRepository;
        $this->memberNotificationService = $memberNotificationService;
    }

    // ここにTransactionはる
    public function purchase(int $shopItemId, int $itemPurchaseCount, MemberId $buyerId, int $buyerPaymentAmount): void
    {
        $shopItem = $this->shopItemRepository->findById($shopItemId);
        if ($shopItem == null) {
            throw new RuntimeException('商品が存在しません。');
        }

        $shippingDetails = $this->createShippingDetails($buyerId, $shopItem->sellerId);
        $deliveryFee = $this->shippingService->calculateDeliveryFee($shippingDetails->fromAddress, $shippingDetails->toAddress);
        $entity = PurchaseEntity::create(
            $this->purchaseRepository->getNextId(),
            $buyerId,
            $shopItemId,
            $shopItem->price,
            $itemPurchaseCount,
            $deliveryFee,
            $shippingDetails,
            $shopItem->inventoryCount);

        $entity->purchase($buyerPaymentAmount);
        $this->purchaseRepository->add($entity);

        // 買い手の決済（省略）
        // 売り手への送金（省略）

        $this->memberNotificationService->notify($buyerId, "取引が完了しました。");
        $this->memberNotificationService->notify($shopItem->sellerId, "商品が売れました。発送してください。");

        // 本当はイベントリスナーで勝手にイベントハンドラーが呼ばれる
        $entity->events->every(function ($value, $key) {
            if ($value instanceof ShopItemInventoryEmptyEvent) {
                $this->handleEvent($value);
            }
        });
    }

    public function registerShipping(int $purchaseId, MemberId $registeredBy, string $trackingNumber) {
        $entity = $this->purchaseRepository->findById($purchaseId);
        if ($entity == null) {
            throw new RuntimeException('購入データがありません。');
        }

        $shopItem = $this->shopItemRepository->findById($entity->purchase->shopItemId);
        if ($shopItem == null) {
            throw new RuntimeException('商品が存在しません。');
        }

        $entity->registerShipping($registeredBy, $shopItem->sellerId, $trackingNumber);
        $this->purchaseRepository->save($entity);

        $this->memberNotificationService->notify($entity->buyerId, "商品の郵送が開始しました。");
    }

    private function createShippingDetails(MemberId $buyerId, MemberId $sellerId)
    {
        Log::emergency(json_encode($buyerId));
        Log::emergency(json_encode($sellerId));
        $buyerAddress = $this->memberRepository->findAddressByMemberId($buyerId);
        $sellerAddress = $this->memberRepository->findAddressByMemberId($sellerId);

        return new ShippingDetails(
            $sellerAddress,
            $buyerAddress,
            null
        );
    }

    public function handleEvent(ShopItemInventoryEmptyEvent $event): void
    {
        $shopItem = $this->shopItemRepository->findById($event->shopItemId);
        if ($shopItem == null) {
            return;
        }

        $this->memberNotificationService->notify($shopItem->sellerId, "商品の在庫がなくなりましたよ。");
    }

}
