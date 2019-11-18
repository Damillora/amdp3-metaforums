<?php
namespace Application\Foundations;

class QueryBuilder {
    private $query = "";
    private $where = "";
    public function select($fields) {
        if(!is_array($fields)) {
            $this->query .= "SELECT ".$fields;
        } else {
            $this->query .= "SELECT ".implode(",",SQLHelper::encode_list($fields));
        }
        return $this;
    }
    public function delete() {
        $this->query .= "DELETE";
        return $this;
    }
    public function update($table) {
        // TODO: SQL injection
        $this->query .= "UPDATE ".$table;
        return $this;
    }
    public function set($data) {
        $this->query .= " SET";
        $final = [];
        foreach($data as $key => $value) { 
            $final[] = $key." = ".SQLHelper::encode_literal($value);
        }
        $this->query .= " ".implode(",",$final);
        return $this;
    }
    public function from($table) {
        // TODO: SQL injection
        $this->query .= " FROM ".$table;
        return $this;
    }
    public function where($a, $b, $c = null) {
        $field = "";
        $value = "";
        $operator = "=";
        if($c == null) {
            // 2 param syntax
            $field = $a;
            $value = $b;
        } else {
            $field = $a;
            $value = $c;
            $operator = $b;
        }
        $value = SQLHelper::encode_literal($value);
        if($this->where == "") {
            $this->where .= " WHERE ".$field." ".$operator." ".$value;
        } else {
            $this->where .= " AND ".$field." ".$operator." ".$value;
        }
        return $this;
    }
    public function orWhere($a, $b, $c = null) {
        $field = "";
        $value = "";
        $operator = "=";
        if($c == null) {
            // 2 param syntax
            $field = $a;
            $value = $b;
        } else {
            $field = $a;
            $value = $c;
            $operator = $b;
        }
        if($this->where == "") {
            $this->where .= " WHERE ".$field." ".$operator." ".$value;
        } else {
            $this->where .= " OR ".$field." ".$operator." ".$value;
        }
        return $this;
    }
    public function build() {
        return $this->query.$this->where;
    }
}
