<?php
namespace Application\Foundations;

use Application\Services\ServiceContainer;

class Model implements \JsonSerializable {
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
        $inst->hydrate($data);
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
    public static function select($where_query) {
        $calling_class = get_called_class();
        $class = explode('\\',get_called_class());
        $tablename = strtolower($class[count($class)-1]);
        $query = new QueryBuilder();
        $query = $query->select('*')->from($tablename)->build();
        $query .= $where_query->build();
        $result = ServiceContainer::Database()->select($query);
        if(count($result) == 0) return [];
        foreach($result as $key => $val) {
            $inst = new $calling_class();
            $inst->hydrate($result[$key]);
            $result[$key] = $inst;
        }
        return $result;
    }
    public static function selectOne($where_query) {
        $calling_class = get_called_class();
        $class = explode('\\',get_called_class());
        $tablename = strtolower($class[count($class)-1]);
        $query = new QueryBuilder();
        $query = $query->select('*')->from($tablename)->build();
        $query .= $where_query->build();
        $result = ServiceContainer::Database()->select($query);
        if(count($result) == 0) return null;
        $inst = new $calling_class();
        $inst->hydrate($result[0]);
        return $inst;
    }
    public static function all() {
        return self::select(new QueryBuilder());
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
        $methodName = $prop."_attribute";
        if(method_exists($this,$methodName)) {
            return $this->$methodName();
        }
        return $this->attributes[$prop];
    }

    function __set($prop, $val) {
        $this->attributes[$prop] = $val;
    }
    public function jsonSerialize() {
        $data = $this->attributes;
        $attr = get_class_methods($this);
        $attr = array_filter($attr, function ($var) {
            return strpos($var, "_attribute") !== false;
        });
        foreach($attr as $attr) {
            $attrName = substr($attr,0,strlen($attr) - strlen("_attribute"));
            $data[$attrName] = $this->$attr();
        }
        return $data;
    }
}
