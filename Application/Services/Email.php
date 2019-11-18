<?php
namespace Application\Services;

use Application\Foundations\MailBuilder;

class Email {
    public static function send($email) {
        mail($email->to,$email->subject,$email->message,$email->headers);
    }
}
