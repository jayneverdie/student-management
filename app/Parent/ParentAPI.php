<?php

namespace App\Parent;

use App\Common\Database;
use App\Common\Message;
use App\Common\JWT;

class ParentAPI
{
  public function __construct() {
		$this->db = Database::connect();
		$this->message = new Message;
	}

  public function all($filter) {
    try {
      return Database::rows(
        $this->db,
        "SELECT 
          P.id
          ,P.relation_id
          ,P.name_prefix_id
          ,N.name_prefix
          ,P.parent_name
          ,P.parent_lastname
          ,P.sex_id
          ,S.sex_description
          ,P.card_id
          ,P.address_first
          ,P.address_second
          ,P.phone
          ,P.birthday
          ,P.education_id
          ,E.education
          ,P.career_id
          ,C.career
          ,P.address_third
          ,P.email
          ,P.status
          ,WB.status_name
          ,P.create_date
          ,P.create_by
          ,P.update_date
          ,P.update_by
        FROM ParentTrans P
        LEFT JOIN NamePrefix N ON P.name_prefix_id = N.id
        LEFT JOIN Sex S ON P.sex_id = S.sex_id 
        LEFT JOIN Education E ON P.education_id = E.id
        LEFT JOIN Career C ON P.career_id = C.id
        LEFT JOIN web_status WB ON P.status = WB.id
        WHERE $filter"
      ); 
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function getNameFix() {
    try {
      return Database::rows(
        $this->db,
        "SELECT *
        FROM NamePrefix"
      );
    } catch (\Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function getSex() {
    try {
      return Database::rows(
        $this->db,
        "SELECT *
        FROM Sex"
      );
    } catch (\Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function getEducation() {
    try {
      return Database::rows(
        $this->db,
        "SELECT *
        FROM Education"
      );
    } catch (\Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function getCareer() {
    try {
      return Database::rows(
        $this->db,
        "SELECT *
        FROM Career"
      );
    } catch (\Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function create(
    $card_id,
    $name_prefix,
    $parent_name,
    $parent_lastname,
    $sex_id,
    $birthday,
    $phone,
    $education,
    $career,
    $email,
    $address_first,
    $address_second,
    $address_third
  ) {

    $isExists = Database::hasRows(
      $this->db,
      "SELECT card_id
      FROM ParentTrans
      WHERE card_id = ?",
      [
        trim($card_id)
      ]
    );

    if ( $isExists === true ) {
      return $this->message->result(false, 'This id already exists!');
    }

    $auth = new JWT;
    $user_data = $auth->verifyToken(); 
    $username = $user_data['data']['user_data']->username;
    $birthday = date('Y-m-d', strtotime($birthday));

    $create = Database::query(
      $this->db,
      "INSERT INTO ParentTrans(
        card_id
        ,name_prefix_id
        ,parent_name
        ,parent_lastname
        ,sex_id
        ,birthday
        ,phone
        ,education_id
        ,career_id
        ,email
        ,address_first
        ,address_second
        ,address_third
        ,status
        ,create_date
        ,create_by
      )
      VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, getdate(), ?)",
      [
        $card_id,
        $name_prefix,
        $parent_name,
        $parent_lastname,
        $sex_id,
        $birthday,
        $phone,
        $education,
        $career,
        $email,
        $address_first,
        $address_second,
        $address_third,
        1,
        $username
      ]
    );

    if ( $create ) {
      return $this->message->result(true, 'Create successful!');
    } else {
      return $this->message->result(false, 'Create failed!');
    }
  }

  public function logsfile($name,$type,$card) {

    $create = Database::query(
      $this->db,
      "INSERT INTO file_document(file_name, file_type, card_id)
      VALUES(?, ?, ?)",
      [
        $name,
        $type,
        $card
      ]
    );

    if ( $create ) {
      return $this->message->result(true, 'Create successful!');
    } else {
      return $this->message->result(false, 'Create failed!');
    }
  }

  public function loadFile($card_id,$filetype) {
    try {
      return Database::rows(
        $this->db,
        "SELECT *
        FROM file_document
        WHERE card_id = ? AND file_type = ?",[$card_id,$filetype]
      );
    } catch (\Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function getMapRelation() {
    try {
      return Database::rows(
        $this->db,
        "SELECT M.*,N.name_prefix,S.student_name,S.student_lastname,C.classroom,R.relation_description
        FROM MapParentStudent M
        LEFT JOIN StudentTrans S ON M.student_id = S.id
        LEFT JOIN NamePrefix N ON S.name_prefix_id = N.id
        LEFT JOIN Relation R ON M.relation = R.id
        LEFT JOIN ClassRoom C ON S.classroom_id = C.id"
      );
    } catch (\Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function getRelation() {
    try {
      return Database::rows(
        $this->db,
        "SELECT *
        FROM Relation"
      );
    } catch (\Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function createMap(
    $map_relation,
    $map_student_id,
    $map_parent_id,
    $map_remark
  ) {

    $isExists = Database::hasRows(
      $this->db,
      "SELECT *
      FROM MapParentStudent
      WHERE parent_id = ? AND student_id = ? AND relation = ?",
      [
        $map_parent_id,$map_student_id,$map_relation
      ]
    );

    if ( $isExists === true ) {
      return $this->message->result(false, 'This id already exists!');
    }

    $auth = new JWT;
    $user_data = $auth->verifyToken(); 
    $username = $user_data['data']['user_data']->username;

    $create = Database::query(
      $this->db,
      "INSERT INTO MapParentStudent(
        parent_id
        ,student_id
        ,relation
        ,remark
        ,create_date
        ,create_by
      )
      VALUES(?, ?, ?, ?, getdate(), ?)",
      [
        $map_parent_id,
        $map_student_id,
        $map_relation,
        $map_remark,
        $username
      ]
    );

    if ( $create ) {
      return $this->message->result(true, 'Create successful!');
    } else {
      return $this->message->result(false, 'Create failed!');
    }
  }

  public function mapUpdate($name, $pk, $value, $table) {
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
  
}