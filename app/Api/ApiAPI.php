<?php

namespace App\Api;

use App\Common\Database;
use App\Common\Automail;
use Webmozart\Assert\Assert;

class ApiAPI {

	private $db_ax = null;
	private $db_live = null;
	private $automail = null;

	public function __construct() {
		$this->db_ax = Database::connect('ax');
		$this->db_live = Database::connect();
		$this->automail = new Automail;
  }

	public function getsubjectBooking() {
		try {
			return 'Booking API ';
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	public function isBookingReviseinternal($filename){
		if (preg_match("/-REV/i", $filename)) {
			return true;
		} else {
			return false;
		}
	}

	public function getMailCustomer($projectId) {
		try {

			$listsTo = [];
			$listsCC = [];
			$listsInternal = [];
			$listsSender = [];

			$query = Database::rows(
				$this->db_live,
				"SELECT * FROM EmailLists WHERE ProjectID=? AND Status=?",[$projectId,1]
			);

			foreach($query as $q) {
				if ($q['EmailType']==1 && $q['EmailCategory']==17) {
					$listsTo[] = $q['Email'];
				}else if($q['EmailType']==2 && $q['EmailCategory']==17){
					$listsCC[] = $q['Email'];
				}else if($q['EmailType']==1 && $q['EmailCategory']==17){
					$listsInternal[] = $q['Email'];
				}else if($q['EmailType']==4 && $q['EmailCategory']==17){
					$listsSender[] = $q['Email'];
				}
			}

			return [
				'to' => $listsTo,
				'cc' => $listsCC,
				'internal' => $listsInternal,
				'sender' => $listsSender
			];

		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	public function getSOFromFileBooking($filename) {
		preg_match_all('/SO(?:..-......)/i', $filename, $data);

		if ( count($data[0]) === 0) {
			return [[]];
		}

		return $data[0];
	}

	public function isBookingTFileMatchAx($filename) {
		try {
			$_SO = "S.SALESID IN ($filename)";
			//	preg_match_all('/SO(?:..-......)/i', $filename, $data);
 			$SO = [];
			$PO = [];
			$PI = [];
			$CY = [];
			$RTN = [];
			$SalName = [];
			$Cusref = [];
			$Loadingdate = [];
			$HC = [];
			$Booking_detail = [];
			$Booknum = [];
			$AGENT = [];

			$query = Database::rows(
				$this->db_ax,
				"SELECT S.SALESID ,
				S.QUOTATIONID,
				S.NoYesId_AddPI,
				S.DSG_CY,
				S.DSG_RTN,
				CT.NAME,
				S.CUSTOMERREF,
				S.DSG_EDDDate,
				DS.DSG_SUBHC,
				DS.DSG_BOOKINGDETAIL,
				S.DSG_PRIMARYAGENTID,
				S.DSG_BOOKINGNUMBER
				FROM SALESTABLE S
				LEFT JOIN DSG_SALESTABLE DS ON DS.SALESID = S.SALESID
				LEFT JOIN CustTable CT ON CT.ACCOUNTNUM = S.CUSTACCOUNT
				WHERE $_SO
				AND S.SALESSTATUS <> 4 --cancel
				AND S.INVOICEACCOUNT IN ('C-2720')
				AND CT.DATAAREAID = 'DSC'"
			);

			foreach($query as $q) {

					$SO[] = $q['SALESID'];
					$PO[] = $q['CUSTOMERREF'];
					$PI[] = $q['QUOTATIONID'];
					$CY[] = date('d/m/Y',strtotime($q['DSG_CY']));
					$RTN[] =date('d/m/Y',strtotime($q['DSG_RTN']));
					$SalName[] = $q['NAME'];
					$Loadingdate[] = date('d/m/Y',strtotime($q['DSG_EDDDate']));
					$HC[] = $q['DSG_SUBHC'];
					$Booking_detail[] = $q['DSG_BOOKINGDETAIL'];
					$Booknum[] = $q['DSG_BOOKINGNUMBER'];
					$AGENT[] = $q['DSG_PRIMARYAGENTID'];

				}
				return [
					"SO" => $SO,
					 "PO" => $PO,
					"PI" => $PI,
					"CY" => $CY,
					"RTN" => $RTN,
					"SalName" => $SalName,
					"Cusref" => $Cusref,
					"Loadingdate" => $Loadingdate,
					"HC" => $HC,
					"Booking_detail" => $Booking_detail,
					"Numbook" => $Booknum,
					"AGENT" => $AGENT
				];
			} catch (\Exception $e) {
					return $e->getMessage();
				}
	}

	public function getBookingBody_v3($txtSo, $txtPo, $txtPI, $txtLd, $txtCy, $txtRtn, $txtHc, $txtBk, $AGENT) {
			$text = '';
			$txtAgent1 = '';
			$txtAgent = '';
			$SALESNAME = 'AMERICAN PACIFIC INDUSTRIES, INC.';;

		//	preg_match_all('/SO(?:..-......)/i', $so, $output_array);
			//
			// if (count($output_array[0])>1) {
			// 	for ($i=0; $i < count($output_array[0]); $i++) {
			// 		$dataso .= $output_array[0][0].",";
			// 		$textSO  = substr($dataso, 0, -1);
			// 	}
			// }else{
			// 	$textSO= $output_array[0][0];
			// }
	    //  ของพี่เจด้ของพี่เจด้านบน
			foreach ($AGENT as $value) {
				if(count($AGENT) >1){
					$txtAgent1 .= $value.',' ;
					$txtAgent = substr($txtAgent1,0,-1);
				}

				else {
								$txtAgent .= $value;
							}
			}
			$text .= 'Dear EL, <br><br>';
			$text .= '<b>Customer name : </b>' . $SALESNAME. '<br>';
			$text .= '<b>P/I : </b>' . $txtPI . '<br>';
			$text .= '<b>SO : </b>' . $txtSo . '<br>';
			$text .= '<b>PO : </b>' .$txtPo. '<br>';
			$text .= '<b>Loading date : </b>'. $txtLd;
			$text .= '<b> CY : </b>'. $txtCy;
			$text .= '<b> RTN : </b>'. $txtRtn.' <br>';
			$text .= '<b>Agent : </b>'. $txtAgent.'<br><br>';
			$text .= '<b>Sub\'HC : </b>'.$txtHc.' <br>';
			$text .= '<b>Booking Detail : </b><br>';

			$text .= '<ul>';

			if($txtBk !="") {
				$text .= '<li>'.$txtBk.'</li>';
			}
			else{
				$text .= '-';
			}
			$text .= '</ul><br>';

			return $text;
 	}

	public function getBookingSubject_internalAPI($SO, $name,$PO, $type, $Numbook ) {
		$text = '';
		//$name = 'AMERICAN PACIFIC INDUSTRIES, INC.';
		$numm = '';
		$numm1 = '';
		$NumbookDuplicate = array_unique( $Numbook ); // $NumbookDuplicate data array
		$arr_Numbook = array_filter( $NumbookDuplicate ); //cut array is null
		$nameTopic = '';
		foreach ($arr_Numbook as $value) {
			if(count($arr_Numbook) >1){
				$numm1 .= $value.',' ;
				$numm = substr($numm1,0,-1);
			}else {
							$numm .= $value;
						}
		}
		foreach ($name as $value) {
			$nameTopic .= $value;
		}
		if($type == 'New'){
			$text .= 'New Booking : ' . $nameTopic . ' / ' .
			$PO . ' / ' .$SO. ' / ' .$numm;
		}else{
			$text .= 'Revised Booking : ' . $nameTopic . ' / ' .
			$PO . ' / ' .$SO. ' / ' .$numm;
		}
		return $text;
	}






}
