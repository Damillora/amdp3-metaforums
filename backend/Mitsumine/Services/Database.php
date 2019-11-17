<?php
namespace Mitsumine\Services;

use Mitsumine\Services\ServiceContainer;

class Database {
    private $conn;
    public function __construct() {
         $config = ServiceContainer::Config();
         $this->conn = mysqli_connect($config->db_host(),$config->db_user(),$config->db_pass(),$config->db_name());
    }
}
