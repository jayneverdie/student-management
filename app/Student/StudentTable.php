<?php

namespace App\Master;

class ParentTable
{
  public function __construct() {
    $this->field =  [
      'id'   => 'id',
      'name' => 'name_prefix'
    ];
    $this->table = 'NamePrefix';
  }
}