<?php

namespace App\Layers\Domain\Member\Value;

class MemberId
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
