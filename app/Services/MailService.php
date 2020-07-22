<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class MailService
{
    public static function mailSend($toUser, $mailClass)
    {
        try {
            retry(2, function () use ($toUser, $mailClass) {
                Mail::to($toUser)->send($mailClass);
            }, 100);

            return true;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
