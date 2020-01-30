<?php

namespace App\Master;

class RelationTable
{
  public function __construct() {
    $this->field =  [
      'id'   => 'id',
      'name' => 'relation_description'
    ];
    $this->table = 'Relation';
  }
}