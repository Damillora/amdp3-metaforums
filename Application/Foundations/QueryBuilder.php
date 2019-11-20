<?php
namespace Application\Foundations;

class QueryBuilder {
    private $query = "";
    private $where = "";
    private $misc = "";
    public function select($fields) {
        if(!is_array($fields)) {
            $this->query .= "SELECT ".$fields;
        } else {
            $this->query .= "SELECT ".implode(",",SQLHelper::encode_list($fields));
        }
        return $this;
    }
    public function selectDistinct($fields) {
        if(!is_array($fields)) {
            $this->query .= "SELECT DISTINCT ".$fields;
        } else {
            $this->query .= "SELECT DISTINCT ".implode(",",SQLHelper::encode_list($fields));
        }
        return $this;
    }
    public function orderBy($column, $order = 'asc') {
        $this->misc .= " ORDER BY ".$column." ".strtoupper($order);
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
        $this->query .= " FROM `".$table."`";
        return $this;
    }
    public function join($table, $condition) {
        $this->query .= " JOIN ".$table." ON ".$condition;
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
    public function whereIn($a, $b) {
        $field = $a;
        $value = SQLHelper::encode_list($b);
        if($this->where == "") {
            $this->where .= " WHERE ".$field." IN (".implode(",",$value).")";
        } else {
            $this->where .= " AND ".$field." IN (".implode(",",$value).")";
        }
        return $this;
    }
    public function whereNotIn($a, $b) {
        $field = $a;
        $value = SQLHelper::encode_list($b);
        if($this->where == "") {
            $this->where .= " WHERE ".$field." NOT IN (".implode(",",$value).")";
        } else {
            $this->where .= " AND ".$field." NOT IN (".implode(",",$value).")";
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
        $value = SQLHelper::encode_literal($value);
        if($this->where == "") {
            $this->where .= " WHERE ".$field." ".$operator." ".$value;
        } else {
            $this->where .= " OR ".$field." ".$operator." ".$value;
        }
        return $this;
    }
    public function limit($limit) {
        $this->misc .= " LIMIT ".$limit;
        return $this;
    }
    public function build() {
        return $this->query.$this->where.$this->misc;
    }
}
