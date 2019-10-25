<?php

namespace App\Services;

use App\Mail\GenericEmail;
use Illuminate\Support\Facades\Mail;

class MailService
{

    public function sendMail($data)
    {
        return Mail::to($data['mailTo'])->send(new GenericEmail($data));
    }
}
