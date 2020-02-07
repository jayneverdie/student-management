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
          ,ROW_NUMBER() OVER(ORDER BY P.id) AS rowid
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

  public function allBy($id,$date,$form_type) {
    try {
      if ($form_type === "send") {
        $columndate = 'send_date'; 
      }else{
        $columndate = 'receive_date'; 
      }

      return Database::rows(
        $this->db,
        "SELECT 
          P.id
          ,R.relation_description
          ,N.name_prefix+P.student_name+' '+P.student_lastname AS FullName
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
        LEFT JOIN Relation R ON MP.relation = R.id
        LEFT JOIN ParentTrans PT ON MP.parent_id = PT.id 
        WHERE PT.card_id = ?
        AND P.id NOT IN
        (
          SELECT student_id
          FROM SendReceiveTime 
          WHERE convert(varchar, $columndate, 23) = ?
        )",[$id,$date]
      ); 
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function allPromote($c_af,$c_bf) {
    try {
      return Database::rows(
        $this->db,
        "SELECT 
        ROW_NUMBER() OVER(ORDER BY S.id) AS rowid,S.id,S.student_id,N.name_prefix+S.student_name+' '+S.student_lastname AS fullname,C.classroom,CC.classroom AS classroom_after
        FROM StudentTrans S
        LEFT JOIN NamePrefix N ON S.name_prefix_id = N.id
        LEFT JOIN ClassRoom C ON S.classroom_id = C.id
        LEFT JOIN ClassRoom CC ON CC.id = ?
        WHERE S.classroom_id = ?",[$c_af,$c_bf]
      ); 
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function allPromoteTemp() {
    try {
      return Database::rows(
        $this->db,
        "SELECT P.promote_from,P.promote_to,C.classroom,CC.classroom AS classroom_after
        FROM Promote P
        LEFT JOIN ClassRoom C ON P.promote_from = C.id
        LEFT JOIN ClassRoom CC ON P.promote_to = CC.id
        WHERE status = ?
        GROUP BY 
        P.promote_from,P.promote_to,C.classroom,CC.classroom",[1]
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

  public function move($student_id,$classroom_before,$classroom_after) {
    try {
      $auth = new JWT;
      $user_data = $auth->verifyToken(); 
      $username = $user_data['data']['user_data']->username;

      for ($i=0; $i < count($student_id); $i++) { 
        $create = Database::query(
          $this->db,
          "INSERT INTO Promote(
            student_id
            ,promote_from
            ,promote_to
            ,status
            ,create_date
            ,create_by
          )
          VALUES(?, ?, ?, ?,getdate(), ?)",
          [
            $student_id[$i],
            $classroom_before,
            $classroom_after,
            1,
            $username
          ]
        );
      }

      if ( $create ) {
        return $this->message->result(true, 'Move successful!');
      } else {
        return $this->message->result(false, 'Move failed!');
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function moveConfirm() {
    try {
      $data = self::allPromoteTemp();
      foreach ($data as $value) {
        $update = Database::query(
          $this->db,
          "UPDATE StudentTrans SET classroom_id = ?
          WHERE classroom_id = ?",[$value['promote_to'],$value['promote_from']]
        );

        $updateTemp = Database::query(
          $this->db,
          "UPDATE Promote SET status = ?
          WHERE promote_to = ? AND promote_from = ?",[0,$value['promote_to'],$value['promote_from']]
        );
      }

      if ( $update ) {
        return $this->message->result(true, 'Move successful!');
      } else {
        return $this->message->result(false, 'Move failed!');
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }

}