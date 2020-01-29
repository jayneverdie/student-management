<?php

namespace App\Teacher;

use App\Common\Database;
use App\Common\Message;
use App\Common\JWT;

class TeacherAPI
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
          ,P.teacher_name
          ,P.teacher_lastname
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
        FROM TeacherTrans P
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
    $teacher_name,
    $teacher_lastname,
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
      FROM TeacherTrans
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
      "INSERT INTO TeacherTrans(
        card_id
        ,name_prefix_id
        ,teacher_name
        ,teacher_lastname
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
        $teacher_name,
        $teacher_lastname,
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

  public function getMapClassroom() {
    try {
      return Database::rows(
        $this->db,
        "SELECT M.*,C.classroom,A.academic_year,A.academic_term
        FROM MapTeacher M
        LEFT JOIN ClassRoom C ON M.classroom_id = C.id
        LEFT JOIN AcademicYear A ON M.academicyear_id = A.id"
      );
    } catch (\Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function getMapStudent() {
    try {
      return Database::rows(
        $this->db,
        "SELECT S.id,S.student_id,S.name_prefix_id,N.name_prefix,S.student_name,S.student_lastname,S.student_nickname,C.classroom
        FROM MapTeacher M
        LEFT JOIN StudentTrans S ON S.classroom_id = M.classroom_id 
        LEFT JOIN NamePrefix N ON N.id = S.name_prefix_id
        LEFT JOIN ClassRoom C ON C.id = S.classroom_id"
      );
    } catch (\Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function getAcademicyear() {
    try {
      return Database::rows(
        $this->db,
        "SELECT *
        FROM AcademicYear"
      );
    } catch (\Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function createMap(
    $map_classroom,
    $map_academicyear,
    $map_teacher_id
  ) {

    $isExists = Database::hasRows(
      $this->db,
      "SELECT *
      FROM MapTeacher
      WHERE teacher_id = ? AND classroom_id = ? AND  academic_term = ?",
      [
        $map_teacher_id,$map_classroom,$map_academicyear
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
      "INSERT INTO MapTeacher(
        teacher_id
        ,classroom_id
        ,academicyear_id
        ,create_date
        ,create_by
      )
      VALUES(?, ?, ?, getdate(), ?)",
      [
        $map_teacher_id,
        $map_classroom,
        $map_academicyear,
        $username
      ]
    );

    if ( $create ) {
      return $this->message->result(true, 'Create successful!');
    } else {
      return $this->message->result(false, 'Create failed!');
    }
  }

}