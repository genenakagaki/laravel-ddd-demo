<?php

namespace App\Layers\Query;

use App\Layers\Domain\Member\Value\MemberId;
use App\Layers\External\Shipping\ShippingService;
use App\Layers\Persistence\Database;
use Illuminate\Support\Facades\Log;

class ShippingQueryService
{
    private Database $db;
    private ShippingService $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->db = app(Database::class);
        $this->shippingService = $shippingService;
    }

    public function getDeliveryFee(MemberId $buyerId, MemberId $sellerId): int {
        $buyer = $this->db->memberList->where('memberId', $buyerId->id)->first();
        $seller = $this->db->memberList->where('memberId', $sellerId->id)->first();

        if ($buyer == null || $seller == null) {
            return 0;
        }

        return $this->shippingService->calculateDeliveryFee($seller->address, $buyer->address);
    }

}
