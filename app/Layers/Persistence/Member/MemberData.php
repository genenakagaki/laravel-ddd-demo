<?php

namespace App\Layers\Persistence\Member;

class MemberData
{
    public int $memberId;
    public string $username;
    public string $email;
    public string $address;

    /**
     * @param int $memberId
     * @param string $username
     * @param string $email
     * @param string $address
     */
    public function __construct(int $memberId, string $username, string $email, string $address)
    {
        $this->memberId = $memberId;
        $this->username = $username;
        $this->email = $email;
        $this->address = $address;
    }


}
