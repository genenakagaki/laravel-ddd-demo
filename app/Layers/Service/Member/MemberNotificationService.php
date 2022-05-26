<?php

namespace App\Layers\Service\Member;

use App\Layers\Domain\Member\Value\MemberId;
use Illuminate\Support\Facades\Log;

class MemberNotificationService
{
    public function notify(MemberId $targetMember, string $message): void {
        // TODO: implement
        Log::alert($message);
    }

}
