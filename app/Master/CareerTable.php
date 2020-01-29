<?php

namespace App\Master;

class CareerTable
{
  public function __construct() {
    $this->field =  [
      'id'   => 'id',
      'name' => 'career'
    ];
    $this->table = 'Career';
  }
}