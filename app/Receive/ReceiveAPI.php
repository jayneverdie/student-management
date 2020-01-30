<?php

namespace App\Receive;

use App\Common\Database;
use App\Common\Message;
use App\Common\JWT;

class ReceiveAPI
{
  	public function __construct() {
		$this->db = Database::connect();
		$this->message = new Message;
	}

	public function all($filter,$dateview) {
	    try {
	      $dateview_start = $dateview." 00:00";
	      $dateview_end = $dateview." 23:59";
	      return Database::rows(
	        $this->db,
	        "SELECT S.id,N.name_prefix,S.student_id,S.student_name,S.student_lastname,S.student_nickname,S.classroom_id,C.classroom,SR.send_date
			FROM StudentTrans S
			LEFT JOIN NamePrefix N ON S.name_prefix_id = N.id
			LEFT JOIN ClassRoom C ON S.classroom_id = C.id
			LEFT JOIN SendReceiveTime SR ON S.id = SR.student_id
			AND SR.send_date >= '$dateview_start'
			AND SR.send_date <= '$dateview_end'
	        WHERE $filter"
	      ); 
	    } catch (Exception $e) {
	      throw new Exception($e->getMessage());
	    }
	}

	public function getStudentByParent($card_id) {
	    try {
	      return Database::rows(
	        $this->db,
	        "SELECT M.parent_id,M.student_id,N.name_prefix,S.student_name,S.student_lastname,S.student_nickname,S.sex_id,S.birthday,S.student_id AS Sstudent_id,P.card_id
			FROM MapParentStudent M
			LEFT JOIN ParentTrans P ON P.id = M.parent_id
			LEFT JOIN StudentTrans S ON S.id = M.student_id
			LEFT JOIN NamePrefix N ON S.name_prefix_id = N.id
			WHERE P.card_id = ?",[$card_id]
	      );
	    } catch (\Exception $e) {
	      throw new Exception($e->getMessage());
	    }
	}

	public function getHours() {
	    try {
	      return Database::rows(
	        $this->db,
	        "SELECT *
	        FROM TimeHours"
	      );
	    } catch (\Exception $e) {
	      throw new Exception($e->getMessage());
	    }
	}

	public function getMinutes() {
	    try {
	      return Database::rows(
	        $this->db,
	        "SELECT *
	        FROM TimeMinutes"
	      );
	    } catch (\Exception $e) {
	      throw new Exception($e->getMessage());
	    }
	}

	public function Send(
	    $send_student_id,
	    $send_parent_id,
	    $send_time,
	    $send_time_hour,
	    $send_time_minute
	) {

		$send_time = date('Y-m-d', strtotime($send_time));
	    $send_chk1 = $send_time." 00:00";
	    $send_chk2 = $send_time." 23:59";
	    
	    $isExists = Database::hasRows(
	      $this->db,
	      "SELECT *
	      FROM SendReceiveTime
	      WHERE send_id = ?
	      AND send_date >= ?
	      AND send_date <= ?",
	      [
	        trim($send_student_id),$send_chk1,$send_chk2
	      ]
	    );

	    if ( $isExists === true ) {
	      return $this->message->result(false, 'This id already exists!');
	    }

	    $auth = new JWT;
	    $user_data = $auth->verifyToken(); 
	    $username = $user_data['data']['user_data']->username;
	    $send_time = $send_time." ".$send_time_hour.":".$send_time_minute;

	    $create = Database::query(
	      $this->db,
	      "INSERT INTO SendReceiveTime(
	        student_id
	        ,send_date
	        ,send_id
	        ,create_date
	        ,create_by
	      )
	      VALUES(?, ?, ?, getdate(), ?)",
	      [
	        $send_student_id,
	        $send_time,
	        $send_parent_id,
	        $username
	      ]
	    );

	    if ( $create ) {
	      return $this->message->result(true, 'Create successful!');
	    } else {
	      return $this->message->result(false, 'Create failed!');
	    }
	}

	public function Read($card_id) {
	    try {
	    	$isExists = Database::hasRows(
		      $this->db,
		      "SELECT *
		      FROM ParentTrans
		      WHERE card_id = ?",
		      [
		        trim($card_id)
		      ]
		    );

		    $isExistsMap = Database::hasRows(
		      $this->db,
		      "SELECT M.*,P.card_id
				FROM MapParentStudent M
				LEFT JOIN ParentTrans P ON M.parent_id = P.id
		      WHERE P.card_id = ?",
		      [
		        trim($card_id)
		      ]
		    );

		    if ( $isExists === false ) {
		      return $this->message->result(false, 'Card Id not found!');
		    }

		    if ( $isExistsMap === false ) {
		      return $this->message->result(false, 'Card Id Map found!');
		    }

	      	return Database::rows(
	        	$this->db,
		        "SELECT MP.parent_id
				      ,MP.student_id
				      ,MP.relation
				      ,MP.remark
					  ,N.name_prefix AS Pname_prefix
					  ,P.parent_name
					  ,P.parent_lastname
					  ,P.card_id
					  ,PX.sex_description AS Psex_id
					  ,P.birthday AS Pbirthday
					  ,P.phone
					  ,S.student_id AS Sstudent_id
					  ,NS.name_prefix AS Sname_prefix
					  ,S.student_name
					  ,S.student_lastname
					  ,S.student_nickname
					  ,SX.sex_description AS Ssex_id
					  ,S.birthday AS Sbirthday
					  ,R.relation_description
				FROM MapParentStudent MP
				LEFT JOIN ParentTrans P ON MP.parent_id = P.id
				LEFT JOIN NamePrefix N ON P.name_prefix_id = N.id
				LEFT JOIN StudentTrans S ON MP.student_id = S.id
				LEFT JOIN NamePrefix NS ON S.name_prefix_id = NS.id
				LEFT JOIN Relation R ON MP.relation = R.id
				LEFT JOIN Sex PX ON P.sex_id = PX.sex_id
				LEFT JOIN Sex SX ON S.sex_id = SX.sex_id
				WHERE P.card_id = ?",[$card_id]
	      	);
	    } catch (\Exception $e) {
	      throw new Exception($e->getMessage());
	    }
	}

}