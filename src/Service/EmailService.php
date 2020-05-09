<?php

namespace App\Service;

use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{


    static function send($user)
    {
        if(Carbon::now()->subYears(18)->isAfter($user->getBirthday())) {
            return true;
        }
            return false;
    }
}