<?php
namespace Application\Foundations;

use Application\Services\ServiceContainer;

class Model {
    public $attributes;
    protected $primary_key = 'id';

    public function __construct() {
    }
    public function hydrate($data) {
        $this->attributes = $data;
    }
    public static function create($data) {
        $calling_class = get_called_class();
        $class = explode('\\',get_called_class());
        $tablename = strtolower($class[count($class)-1]);
        $result = ServiceContainer::Database()->insert($tablename, $data);
        if($result) {
            $data['id'] = $result;
        }
        $inst = new $calling_class();
        $inst->hydrate($result);
        return $inst;
    }
    public static function find($key) {
        $calling_class = get_called_class();
        $inst = new $calling_class();
        $class = explode('\\',get_called_class());
        $tablename = strtolower($class[count($class)-1]);
        $query = new QueryBuilder();
        $query = $query->select('*')->from($tablename)->where($inst->primary_key,$key)->build();
        $result = ServiceContainer::Database()->select($query);
        if(count($result) == 0) return null;
        $inst->hydrate($result[0]);
        return $inst;
    }
    public function update($key) {
        $calling_class = get_called_class();
        $class = explode('\\',get_called_class());
        $tablename = strtolower($class[count($class)-1]);
        $query = new QueryBuilder();
        $query = $query->update($tablename)->set($key)->where($this->primary_key,$this->attributes[$this->primary_key])->build();
        $result = ServiceContainer::Database()->update($query);
        if(!$result) return null;
        else {
           return $this;
        }
    }
    public function delete() {
        $calling_class = get_called_class();
        $class = explode('\\',get_called_class());
        $tablename = strtolower($class[count($class)-1]);
        $query = new QueryBuilder();
        $query = $query->delete()->from($tablename)->where($this->primary_key,$this->attributes[$this->primary_key])->build();
        $result = ServiceContainer::Database()->update($query);
        if(!$result) return $this;
        else {
           return null;
        }
    }
    function __get($prop) {
        return $this->attributes[$prop];
    }

    function __set($prop, $val) {
        $this->attributes[$prop] = $val;
    }

}
