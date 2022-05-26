<?php

namespace App\Layers\Web\Controller\Shop;

use App\Layers\Domain\Member\Value\MemberId;
use App\Layers\Domain\Shop\Value\PurchaseDetails;
use App\Layers\Domain\Shop\Value\SalesDetails;
use App\Layers\Domain\Shop\Value\ShippingDetails;
use App\Layers\Query\ShippingQueryService;
use App\Layers\Query\ShopItemQueryService;
use App\Layers\Repository\Shop\ShopItemRepository;
use App\Layers\Service\Shop\PurchaseService;
use App\Layers\Service\Shop\ShopService;
use App\Layers\Web\Controller\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShopController extends Controller
{
    private ShopItemQueryService $shopItemQueryService;
    private ShippingQueryService $shippingQueryService;
    private PurchaseService $purchaseService;

    // ログインしてることにする
    private MemberId $loggedInUser;

    public function __construct(ShopItemQueryService $shopItemQueryService, ShippingQueryService $shippingQueryService, PurchaseService $purchaseService)
    {
        $this->shopItemQueryService = $shopItemQueryService;
        $this->shippingQueryService = $shippingQueryService;
        $this->purchaseService = $purchaseService;
        $this->loggedInUser = new MemberId(1002);
    }

    // /
    public function index(Request $request): View
    {
        return view('index', [
            'data' => $this->shopItemQueryService->findAll()
        ]);
    }

    // /shopItem/{shopItemId}
    public function shopItemDetails(Request $request, int $shopItemId): View
    {
        return view('shopItem', [
            'data' => $this->shopItemQueryService->findById($shopItemId),
        ]);
    }

    // /shopItem/{shopItemId}/billing/{purchaseCount}
    public function getBillingInfo(Request $request, int $shopItemId, int $purchaseCount): string
    {
        $shopItemQuery = $this->shopItemQueryService->findById($shopItemId);
        $deliveryFee = $this->shippingQueryService->getDeliveryFee($this->loggedInUser, new MemberId($shopItemQuery->sellerId));
        return json_encode(SalesDetails::create($shopItemQuery->price, $purchaseCount, $deliveryFee));
    }

    public function purchase(Request $request): void
    {
        $this->purchaseService->purchase(
            $request->shopItemId,
            $request->itemPurchaseCount,
            new MemberId($request->buyerId),
            $request->buyerPaymentAmount,
        );
    }
}
