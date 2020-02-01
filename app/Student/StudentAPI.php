<?php

namespace App\Student;

use App\Common\Database;
use App\Common\Message;
use App\Common\JWT;

class StudentAPI
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
          ,P.name_prefix_id
          ,N.name_prefix
          ,P.student_name
          ,P.student_lastname
          ,P.student_id
          ,P.student_nickname
          ,P.sex_id
          ,S.sex_description
          ,P.card_id
          ,P.address_first
          ,P.address_second
          ,P.phone
          ,P.birthday
          ,P.attendance_date
          ,P.classroom_id
          ,C.classroom
          ,P.status
          ,WB.status_name
          ,P.create_date
          ,P.create_by
          ,P.update_date
          ,P.update_by
        FROM StudentTrans P
        LEFT JOIN NamePrefix N ON P.name_prefix_id = N.id
        LEFT JOIN Sex S ON P.sex_id = S.sex_id 
        LEFT JOIN ClassRoom C ON P.classroom_id = C.id
        LEFT JOIN web_status WB ON P.status = WB.id
        WHERE $filter"
      ); 
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function allBy($id) {
    try {
      return Database::rows(
        $this->db,
        "SELECT 
          P.id
          ,P.name_prefix_id
          ,N.name_prefix
          ,P.student_name
          ,P.student_lastname
          ,P.student_id
          ,P.student_nickname
          ,P.sex_id
          ,S.sex_description
          ,P.card_id
          ,P.address_first
          ,P.address_second
          ,P.phone
          ,P.birthday
          ,P.attendance_date
          ,P.classroom_id
          ,C.classroom
          ,P.status
          ,WB.status_name
          ,P.create_date
          ,P.create_by
          ,P.update_date
          ,P.update_by
          ,MP.parent_id
          ,PT.card_id
        FROM StudentTrans P
        LEFT JOIN NamePrefix N ON P.name_prefix_id = N.id
        LEFT JOIN Sex S ON P.sex_id = S.sex_id 
        LEFT JOIN ClassRoom C ON P.classroom_id = C.id
        LEFT JOIN web_status WB ON P.status = WB.id
        LEFT JOIN MapParentStudent MP ON P.id = MP.student_id
        LEFT JOIN ParentTrans PT ON MP.parent_id = PT.id 
        WHERE PT.card_id = ?",[$id]
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
        FROM NamePrefix WHERE id IN (4,5)"
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

  public function getClassroom() {
    try {
      return Database::rows(
        $this->db,
        "SELECT *
        FROM ClassRoom"
      );
    } catch (\Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function create(
    $card_id,
    $nickname,
    $name_prefix,
    $student_name,
    $student_lastname,
    $sex_id,
    $birthday,
    $phone,
    $attendance,
    $classroom,
    $address_first,
    $address_second,
    $remark,
    $cardid_father,
    $cardid_mother
  ) {

    $isExists = Database::hasRows(
      $this->db,
      "SELECT student_id
      FROM StudentTrans
      WHERE student_id = ?",
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
    $attendance = date('Y-m-d', strtotime($attendance));

    $create = Database::query(
      $this->db,
      "INSERT INTO StudentTrans(
        student_id
        ,student_nickname
        ,name_prefix_id
        ,student_name
        ,student_lastname
        ,sex_id
        ,birthday
        ,phone
        ,attendance_date
        ,classroom_id
        ,address_first
        ,address_second
        ,remark
        ,card_id_father
        ,card_id_mother
        ,status
        ,create_date
        ,create_by
      )
      VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, getdate(), ?)",
      [
        $card_id,
        $nickname,
        $name_prefix,
        $student_name,
        $student_lastname,
        $sex_id,
        $birthday,
        $phone,
        $attendance,
        $classroom,
        $address_first,
        $address_second,
        $remark,
        $cardid_father,
        $cardid_mother,
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

}