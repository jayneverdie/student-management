<?php

namespace App\Master;

class PrefixTable
{
  public function __construct() {
    $this->field =  [
      'id'   => 'id',
      'name' => 'name_prefix'
    ];
    $this->table = 'NamePrefix';
  }
}