<?php

namespace App\Layers\Repository\Member;

use App\Layers\Domain\Member\Value\MemberId;
use App\Layers\Persistence\Database;
use App\Layers\Persistence\Member\MemberData;
use App\Layers\Persistence\Member\MemberDataList;
use Illuminate\Support\Collection;

class MemberRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = app(Database::class);
    }

    public function findAddressByMemberId(MemberId $memberId): string
    {
        return $this->db->memberList->where('memberId', $memberId->id)->first()->address;
    }

}
