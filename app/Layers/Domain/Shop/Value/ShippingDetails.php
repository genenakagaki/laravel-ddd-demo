<?php

namespace App\Layers\Domain\Shop\Value;

class ShippingDetails
{
    public string $fromAddress;
    public string $toAddress;
    public ?string $trackingNumber;

    public function __construct(string $fromAddress, string $toAddress, ?string $trackingNumber)
    {
        $this->fromAddress = $fromAddress;
        $this->toAddress = $toAddress;
        $this->trackingNumber = $trackingNumber;
    }
}
