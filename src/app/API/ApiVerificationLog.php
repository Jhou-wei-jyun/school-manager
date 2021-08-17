<?php

namespace App\API;

use App\VerificationLog;

trait ApiVerificationLog
{
    function VerificationLog($phone, $api, $sent)
    {
        $log = new VerificationLog([
            'phone' => $phone,
            'api' => $api,
            'sent' => $sent,
        ]);
        $log->save();
    }
}
