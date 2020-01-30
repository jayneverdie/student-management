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

    $auth = new JWT;
    $user_data = $auth->verifyToken(); 
    $username = $user_data['data']['user_data']->username;

    $update = Database::query(
      $this->db,
      "UPDATE $table
      SET $name = ?
      , update_by = ?
      , update_date = getdate()
      WHERE id = ?",
      [
        $value,
        $username,
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

  public function careerAll($filter) {
    return Database::rows(
      $this->db,
      "SELECT 
        N.id
        ,N.career
        ,N.create_date
        ,N.create_by
        ,N.update_date
        ,N.update_by
      FROM Career N
      WHERE $filter"
    );
  }

  public function careerCreate($name) {

    $isExists = Database::hasRows(
      $this->db,
      "SELECT career 
      FROM Career
      WHERE career = ?",
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
      "INSERT INTO Career(career, create_by, create_date)
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

  public function careerDelete($id) {

    $isUsingParent = Database::hasRows(
      $this->db,
      "SELECT career_id 
      FROM ParentTrans
      WHERE career_id = ?",
      [
        $id
      ]
    );

    $isUsingTeacher = Database::hasRows(
      $this->db,
      "SELECT career_id 
      FROM TeacherTrans
      WHERE career_id = ?",
      [
        $id
      ]
    );

    if ( $isUsingParent === true || $isUsingTeacher === true ) {
      return $this->message->result(false, 'This namefix is using!');
    }

    $delete = Database::query(
      $this->db,
      "DELETE FROM Career
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

  public function relationAll($filter) {
    return Database::rows(
      $this->db,
      "SELECT 
        N.id
        ,N.relation_description
        ,N.create_date
        ,N.create_by
        ,N.update_date
        ,N.update_by
      FROM Relation N
      WHERE $filter"
    );
  }

  public function relationCreate($name) {

    $isExists = Database::hasRows(
      $this->db,
      "SELECT relation_description 
      FROM Relation
      WHERE relation_description = ?",
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
      "INSERT INTO Relation(relation_description, create_by, create_date)
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

  public function relationDelete($id) {

    $isUsingMapParent = Database::hasRows(
      $this->db,
      "SELECT relation 
      FROM MapParentStudent
      WHERE relation = ?",
      [
        $id
      ]
    );

    if ( $isUsingMapParent ) {
      return $this->message->result(false, 'This namefix is using!');
    }

    $delete = Database::query(
      $this->db,
      "DELETE FROM Relation
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

  public function educationAll($filter) {
    return Database::rows(
      $this->db,
      "SELECT 
        N.id
        ,N.education
        ,N.create_date
        ,N.create_by
        ,N.update_date
        ,N.update_by
      FROM Education N
      WHERE $filter"
    );
  }

  public function educationCreate($name) {

    $isExists = Database::hasRows(
      $this->db,
      "SELECT education 
      FROM Education
      WHERE education = ?",
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
      "INSERT INTO Education(education, create_by, create_date)
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

  public function educationDelete($id) {

    $isUsingParent = Database::hasRows(
      $this->db,
      "SELECT education_id 
      FROM ParentTrans
      WHERE education_id = ?",
      [
        $id
      ]
    );

    $isUsingTeacher = Database::hasRows(
      $this->db,
      "SELECT education_id 
      FROM TeacherTrans
      WHERE education_id = ?",
      [
        $id
      ]
    );

    if ( $isUsingParent === true || $isUsingTeacher === true ) {
      return $this->message->result(false, 'This namefix is using!');
    }

    $delete = Database::query(
      $this->db,
      "DELETE FROM Education
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

  public function getAllRelation() {
    return Database::rows(
      $this->db,
      "SELECT * FROM Relation"
    );
  }
  
}