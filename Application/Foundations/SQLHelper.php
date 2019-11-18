<?php
namespace Application\Foundations;

use Application\Services\ServiceContainer;

class SQLHelper {
    
    public static function encode_list($data) {
         $insert_data = $data;
         foreach($insert_data as $index => $val) {
             $insert_data[$index] = SQLHelper::encode_literal($val);
         }
         return $insert_data;
    }
    public static function encode_literal($val) {
             $db = ServiceContainer::Database();
             if(is_numeric($val)) {
                 return $val;
             } else if(is_null($val)) {
                 return 'NULL';
             } else if(!is_numeric($val)) {
                 return '"'.$db->escapeString($val).'"';
             } else if($val == "") {
                 return '""';
             } else {
                 return $val;
             }
    }
}
