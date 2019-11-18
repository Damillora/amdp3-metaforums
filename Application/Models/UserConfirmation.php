<?php
namespace Application\Models;

use Application\Foundations\Model as DBModel;

class UserConfirmation extends DBModel {
    protected $primary_key = 'confirm_key';
}
