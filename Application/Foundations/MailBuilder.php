<?php
namespace Application\Foundations;

class MailBuilder {
    public $to = "";
    public $subject = "";
    public $message = "";
    public $headers = "";

    public function to($to) {
        $this->to = $to;    
        return $this;
    }
    public function subject($subject) {
        $this->subject = $subject;
        return $this;
    }
    public function body($body) {
        $this->message = $body;
        return $this;
    }
    public function from($from) {
        $this->headers .= "From: ".$from."\r\n";
        return $this;
    }
}
