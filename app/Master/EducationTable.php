<?php

namespace App\Master;

class EducationTable
{
  public function __construct() {
    $this->field =  [
      'id'   => 'id',
      'name' => 'education'
    ];
    $this->table = 'Education';
  }
}