<?php

namespace App\Parent;

class ParentTable
{
  public function __construct() {
    $this->field =  [
      'id'   => 'id',
      'parent_name' => 'parent_name',
      'parent_lastname' => 'parent_lastname',
      'phone' => 'phone',
      'email' => 'email'
    ];
    $this->table = 'ParentTrans';
  }
}