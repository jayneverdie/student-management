<?php

namespace App\Master;

class ClassroomTable
{
  public function __construct() {
    $this->field =  [
      'id'   => 'id',
      'name' => 'classroom'
    ];
    $this->table = 'Classroom';
  }
}