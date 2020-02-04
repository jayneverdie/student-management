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
	        "SELECT S.id,
	        		N.name_prefix,
	        		S.student_id,
	        		S.student_name,
	        		S.student_lastname,
	        		S.student_nickname,
					S.classroom_id,
					C.classroom
			,(SELECT CONVERT(varchar,send_date,103) +' '+ SUBSTRING(CONVERT(varchar,send_date,108),1,5) FROM SendReceiveTime WHERE send_date >='$dateview_start' AND send_date <= '$dateview_end' AND student_id=S.id) AS send_date
			,(SELECT XPR.parent_name+' '+XPR.parent_lastname
			FROM SendReceiveTime XR
			LEFT JOIN ParentTrans XPR ON XR.send_id = XPR.id
			WHERE XR.send_date >='$dateview_start' AND XR.send_date <= '$dateview_end' AND XR.student_id=S.id) AS parent_fullname_send
			,(SELECT CONVERT(varchar,receive_date,103) +' '+ SUBSTRING(CONVERT(varchar,send_date,108),1,5) FROM SendReceiveTime WHERE receive_date >='$dateview_start' AND receive_date <= '$dateview_end' AND student_id=S.id) AS receive_date
			,(SELECT XPR.parent_name+' '+XPR.parent_lastname
			FROM SendReceiveTime XR
			LEFT JOIN ParentTrans XPR ON XR.receive_id = XPR.id
			WHERE XR.receive_date >='$dateview_start' AND XR.receive_date <= '$dateview_end' AND XR.student_id=S.id) AS parent_fullname_receive
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

	public function getStudentById($id) {
	    try {
	      return Database::rows(
	        $this->db,
	        "SELECT S.*,SX.sex_description,N.name_prefix
			FROM StudentTrans S
			LEFT JOIN Sex SX ON S.sex_id = SX.sex_id
			LEFT JOIN NamePrefix N ON S.name_prefix_id = N.id
			WHERE S.id = ?",[$id]
	      );
	    } catch (\Exception $e) {
	      throw new Exception($e->getMessage());
	    }
	}

	public function getRelationByStudent($student_id,$parent_id) {
	    try {
	      return Database::rows(
	        $this->db,
	        "SELECT MP.parent_id
			      ,MP.student_id
			      ,MP.relation
			      ,MP.remark
				  ,P.card_id
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
			LEFT JOIN StudentTrans S ON MP.student_id = S.id
			LEFT JOIN NamePrefix NS ON S.name_prefix_id = NS.id
			LEFT JOIN Relation R ON MP.relation = R.id
			LEFT JOIN Sex SX ON S.sex_id = SX.sex_id
			WHERE MP.student_id = ? AND P.card_id = ?",[$student_id,$parent_id]
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
	    
	    $auth = new JWT;
	    $user_data = $auth->verifyToken(); 
	    $username = $user_data['data']['user_data']->username;
	    $send_time = $send_time." ".$send_time_hour.":".$send_time_minute;

    	for ($i=0; $i < count($send_student_id); $i++) { 
    		$isExists = Database::hasRows(
		      $this->db,
		      "SELECT *
		      FROM SendReceiveTime
		      WHERE send_id = ?
		      AND student_id = ?
		      AND send_date >= ?
		      AND send_date <= ?",
		      [
		        $send_parent_id,$send_student_id[$i],$send_chk1,$send_chk2
		      ]
		    );

		    if ( $isExists === true ) {
		      return $this->message->result(false, 'This id already exists!');
		    }

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
		        $send_student_id[$i],
		        $send_time,
		        $send_parent_id,
		        $username
		      ]
		    );
    	}

	    if ( $create ) {
	      return $this->message->result(true, 'Create successful!');
	    } else {
	      return $this->message->result(false, 'Create failed!');
	    }
	}

	public function Receive(
	    $send_student_id,
	    $send_parent_id,
	    $send_time,
	    $send_time_hour,
	    $send_time_minute
	) {

		$send_time = date('Y-m-d', strtotime($send_time));
	    $send_chk1 = $send_time." 00:00";
	    $send_chk2 = $send_time." 23:59";

	    $auth = new JWT;
	    $user_data = $auth->verifyToken(); 
	    $username = $user_data['data']['user_data']->username;
	    $send_time = $send_time." ".$send_time_hour.":".$send_time_minute;
	    
	    for ($i=0; $i < count($send_student_id); $i++) { 
		    $isExists = Database::hasRows(
		      $this->db,
		      "SELECT *
		      FROM SendReceiveTime
		      WHERE receive_id = ?
		      AND student_id = ?
		      AND receive_date >= ?
		      AND receive_date <= ?",
		      [
		        $send_parent_id,$send_student_id[$i],$send_chk1,$send_chk2
		      ]
		    );

		    if ( $isExists === true ) {
		      return $this->message->result(false, 'This id already exists!');
		    }
		}

		for ($i=0; $i < count($send_student_id); $i++) { 
		    $isExistsSend = Database::hasRows(
		      $this->db,
		      "SELECT *
		      FROM SendReceiveTime
		      WHERE send_id = ?
		      AND student_id = ?
		      AND send_date >= ?
		      AND send_date <= ?",
		      [
		        $send_parent_id,$send_student_id[$i],$send_chk1,$send_chk2
		      ]
		    );

		    if ( $isExistsSend === true ) {
		    	$create = Database::query(
			      $this->db,
			      "UPDATE SendReceiveTime 
			      SET receive_date = ?, receive_id = ?, update_date = getdate(), update_by = ?
			      WHERE student_id = ? 
			      AND send_date >= ?
		      	  AND send_date <= ?",
			      [
			      	$send_time,
			      	$send_parent_id,
			        $username,
			        $send_student_id[$i],
			        $send_chk1,
			        $send_chk2
			      ]
			    );
		    }else{
		    	$create = Database::query(
			      $this->db,
			      "INSERT INTO SendReceiveTime(
			        student_id
			        ,receive_date
			        ,receive_id
			        ,create_date
			        ,create_by
			      )
			      VALUES(?, ?, ?, getdate(), ?)",
			      [
			        $send_student_id[$i],
			        $send_time,
			        $send_parent_id,
			        $username
			      ]
			    );
		    }
		}

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

	public function Delete(
	    $student_id,
	    $dateview
	) {

		$send_time = date('Y-m-d', strtotime($dateview));
	    $send_chk1 = $send_time." 00:00";
	    $send_chk2 = $send_time." 23:59";

	    $auth = new JWT;
	    $user_data = $auth->verifyToken(); 
	    $username = $user_data['data']['user_data']->username;

	    $isExists = Database::hasRows(
	      $this->db,
	      "SELECT *
	      FROM SendReceiveTime
	      WHERE student_id = ?
	      AND send_date >= ?
	      AND send_date <= ?",
	      [
	        $student_id,$send_chk1,$send_chk2
	      ]
	    );

	    if ( $isExists === false ) {
	      return $this->message->result(false, 'student is not send or receive!');
	    }

    	$delete = Database::query(
	      $this->db,
	      "DELETE FROM SendReceiveTime 
	      WHERE student_id = ? 
	      AND send_date >= ?
      	  AND send_date <= ?",
	      [
	      	$student_id,
	        $send_chk1,
	        $send_chk2
	      ]
	    );

	    if ( $delete ) {
	      return $this->message->result(true, 'Delete successful!');
	    } else {
	      return $this->message->result(false, 'Delete failed!');
	    }
	}

}