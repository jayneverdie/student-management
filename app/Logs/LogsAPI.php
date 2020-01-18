<?php

namespace App\Logs;

use App\Common\Database;
use App\Common\Message;
use App\Common\JWT;

class LogsAPI
{
	public function __construct() {
		$this->db = Database::connect();
		$this->message = new Message;
	}

	public function InsertLogs($name, $pk, $value, $table) {
		$auth = new JWT;
		$user_data = $auth->verifyToken(); 
		$username = $user_data['data']['user_data']->username;
		
	    $insert = Database::query(
	      $this->db,
	      "INSERT INTO LogsTable(LogsTable,LogsColumn,LogsValue,LogsValueID,UserID,LogsDate) VALUES(?,?,?,?,?,getdate())",
	      [
	      	$table,
	        $name,
	        $value,
	        $pk,
	        $username
	      ]
	    );

	    if ( $insert ) {
	      return $this->message->result(true, 'InsertLogs successful!');
	    } else {
	      return $this->message->result(false, 'InsertLogs failed!');
	    }
	}

	public function EmailLists($filter,$column) {
		return Database::rows(
			$this->db,
			"SELECT ID,
					LogsTable,
					LogsColumn,
					LogsValue,
					LogsValueID,
					UserID,
					CONVERT(VARCHAR, LogsDate, 103) AS LogsDate,
					LEFT(CONVERT(VARCHAR, LogsDate, 108), 5) AS LogsTime
			FROM LogsTable
			WHERE $filter 
			AND LogsColumn=?
			AND LogsTable=?
			ORDER BY LogsDate,ID DESC",[$column,'EmailLists']
		);
	}

	public function ListsColumn($table) {
		return Database::rows(
			$this->db,
			"SELECT LogsColumn
			FROM LogsTable WHERE LogsTable = ?
			GROUP BY LogsColumn",[$table]
		);
	}

	public function allLogSenmail($filter) {
		return Database::rows(
			$this->db,
			"SELECT Top 100 LM.ID
			      ,LM.LogsID
			      ,LM.Email
			      ,ET.Description AS EmailType
			      ,LM.SendDate
			      ,P.ProjectName
			FROM LogsSendMail LM
			LEFT JOIN EmailType ET ON LM.EmailType = ET.ID
			LEFT JOIN Logs L ON LM.LogsID = L.ID
			LEFT JOIN  Project P ON L.ProjectID = P.ProjectID
			WHERE $filter 
			ORDER BY LM.SendDate DESC"
		);
	}

	public function allLogs($filter) {
		return Database::rows(
			$this->db,
			"SELECT TOP 100 
			   L.ID
			  ,L.ProjectID
			  ,P.ProjectName
			  ,L.Message
			  ,L.CustomerCode
			  ,L.SO
			  ,L.PI
			  ,L.QA
			  ,CASE 
			  WHEN L.Invoice IS NULL THEN '-'
			  ELSE L.Invoice END Invoice
			  ,CASE 
			  WHEN L.FileName IS NULL THEN '-'
			  ELSE L.FileName END FileName
			  ,L.Source
			  ,L.SendDate
			FROM Logs L
			LEFT JOIN Project P ON L.ProjectID = P.ProjectID
			WHERE Message != 'Keep File' AND $filter
			ORDER BY L.ID DESC"
		);
	}

}