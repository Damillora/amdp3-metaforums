<?php
namespace Application\Services;

use Application\Services\ServiceContainer;
use Application\Foundations\SQLHelper;

class Database {
    private $conn;
    private $config;
    public function __construct() {
         $this->config = ServiceContainer::Config();
         $this->conn = mysqli_connect($this->config->db_host(),$this->config->db_user(),$this->config->db_pass(),$this->config->db_name());
    }
    public function insert($table, $data) {
         $insert_data = SQLHelper::encode_list($data);
         $key_names = array_keys($insert_data);
         $query = "INSERT INTO ".$table." (".implode(",",$key_names).") VALUES (".implode(",",$insert_data).")";
         $result = mysqli_query($this->conn,$query);
         if($result) {
             return mysqli_insert_id($this->conn);
         } else {
             echo mysqli_error($this->conn);
             return null;
         } 
    }
    public function update($query) {
         $result = mysqli_query($this->conn,$query);
         return $result;
    }
    public function select($query) {
         $result = mysqli_query($this->conn,$query);
         if($result) {
             return mysqli_fetch_all($result,MYSQLI_ASSOC);
         } else {
             return null;
         }
    }

    // Escaping strings requires DB connection, which is only handled by the Database service.
    public function escapeString($str) {
        return mysqli_real_escape_string($this->conn,$str);
    }
}
