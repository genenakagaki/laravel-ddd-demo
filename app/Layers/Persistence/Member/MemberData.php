<?php

namespace App\Layers\Repository\Member\Data;

class MemberData
{
    public int $memberId;
    public string $email;
    public string $address;

    /**
     * @param int $memberId
     * @param string $email
     * @param string $address
     */
    public function __construct(int $memberId, string $email, string $address)
    {
        $this->memberId = $memberId;
        $this->email = $email;
        $this->address = $address;
    }

}
