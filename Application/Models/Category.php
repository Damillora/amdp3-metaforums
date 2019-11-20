<?php
namespace Application\Models;

use Application\Foundations\Model as DBModel;
use Application\Foundations\QueryBuilder;

class Category extends DBModel {
  public function moderators_attribute() {
    $query = new QueryBuilder(); 
    $query = $query->where('category_id',$this->id); 
    $moderators = ModeratorCategory::select($query); 
    if(count($moderators) == 0) return [];
    $moderators = array_map(function($a) {
      return $a->user_id;
    }, $moderators);
    $query = new QueryBuilder();
    $query = $query->whereIn('id',$moderators);
    return User::select($query);
  }
  public function group() {
    return Group::find($this->group_id);
  }
}
