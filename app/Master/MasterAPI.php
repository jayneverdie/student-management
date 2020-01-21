<?php

namespace App\Master;

use App\Common\Database;
use App\Common\Message;
use App\Common\JWT;

class MasterAPI
{
  public function __construct() {
		$this->db = Database::connect();
		$this->message = new Message;
	}

  public function all($filter) {
    return Database::rows(
      $this->db,
      "SELECT 
        N.id
        ,N.name_prefix
        ,N.create_date
        ,N.create_by
        ,N.update_date
        ,N.update_by
      FROM NamePrefix N
      WHERE $filter"
    );
  }

  public function update($name, $pk, $value, $table) {
    $update = Database::query(
      $this->db,
      "UPDATE $table
      SET $name = ?
      WHERE id = ?",
      [
        $value,
        $pk
      ]
    );

    if ( $update ) {
      return $this->message->result(true, 'Update successful!');
    } else {
      return $this->message->result(false, 'Update failed!');
    }
  }

  public function create($name) {

    $isExists = Database::hasRows(
      $this->db,
      "SELECT name_prefix 
      FROM NamePrefix
      WHERE name_prefix = ?",
      [
        trim($name)
      ]
    );

    if ( $isExists === true ) {
      return $this->message->result(false, 'This name already exists!');
    }

    $auth = new JWT;
    $user_data = $auth->verifyToken(); 
    $username = $user_data['data']['user_data']->username;

    $create = Database::query(
      $this->db,
      "INSERT INTO NamePrefix(name_prefix, create_by, create_date)
      VALUES(?, ?, getdate())",
      [
        $name,
        $username
      ]
    );

    if ( $create ) {
      return $this->message->result(true, 'Create successful!');
    } else {
      return $this->message->result(false, 'Create failed!');
    }
  }

  public function delete($id) {

    $isUsingParent = Database::hasRows(
      $this->db,
      "SELECT name_prefix_id 
      FROM ParentTrans
      WHERE name_prefix_id = ?",
      [
        $id
      ]
    );

    $isUsingTeacher = Database::hasRows(
      $this->db,
      "SELECT name_prefix_id 
      FROM TeacherTrans
      WHERE name_prefix_id = ?",
      [
        $id
      ]
    );

    $isUsingStudent = Database::hasRows(
      $this->db,
      "SELECT name_prefix_id 
      FROM StudentTrans
      WHERE name_prefix_id = ?",
      [
        $id
      ]
    );

    if ( $isUsingParent === true || $isUsingTeacher === true || $isUsingStudent === true ) {
      return $this->message->result(false, 'This namefix is using!');
    }

    $delete = Database::query(
      $this->db,
      "DELETE FROM NamePrefix
      WHERE id = ?",
      [
        $id
      ]
    );

    if ( $delete ) {
      return $this->message->result(true, 'Delete successful!');
    } else {
      return $this->message->result(false, 'Delete failed!');
    }
  }

}