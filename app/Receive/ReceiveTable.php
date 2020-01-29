<?php

namespace App\Master;

class ReceiveTable
{
  public function __construct() {
    $this->field =  [
      'id'   => 'id',
      'name' => 'name_prefix'
    ];
    $this->table = 'SendReceiveTime';
  }
}