<?php

namespace App\Api;

use App\Common\View;
use App\Api\ApiAPI;
use App\Common\Automail;
use App\Email\EmailAPI;
use App\Common\Datatables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ApiController {

	public function __construct() {
		$this->view = new View;
		$this->api = new ApiAPI;
		$this->automail = new Automail;
		$this->email = new EmailAPI;
		$this->datatables = new Datatables;
	}

	public function booking($request, $response, $args) {
			try {
					$projectId = 37;
          $root = 'files/api/booking/';
          $rootTemp = 'temp/api/booking/';
					$fileOkay = [];
				 	$SOSend = "";
					$files = $this->automail->getDirRoot($root);
					$getMail = $this->api->getMailCustomer($projectId);

					if (count($files)===0) {
						echo "The file does not exist";
						exit();
	        }
					foreach ($files as $f) {
						 $so = $this->api->getSOFromFileBooking($f);
						 foreach ($so as $key => $value) {
							$SOSend .= "'".$value."'".",";
							$dataso = substr($SOSend, 0, -1);
					   }
					 	  $SOSend ="";
							if ($this->api->isBookingReviseinternal($f) === true) {
								 $dataGet = $this->api->isBookingTFileMatchAx($dataso);
								 $SOS = $dataGet["SO"];
								 $PO = $dataGet["PO"];
								 $PI = $dataGet["PI"];
								 $CY = $dataGet["CY"];
								 $RTN = $dataGet["RTN"];
								 $SalName = $dataGet["SalName"];
								 $Numbook = $dataGet["Numbook"];
								 $Loadingdate = $dataGet["Loadingdate"];
								 $SubHC = $dataGet["HC"];
								 $Booking_detail = $dataGet["Booking_detail"];
								 $AGENT = $dataGet["AGENT"];
								 $Type = "Revice";
								 if ($SOS !== "" ) {
									 $fileOkay[] = [
										 'file' => $f,
										 'SO' => array_unique($SOS),
										 'PO' => array_unique($PO),
										 'PI' => array_unique($PI),
										 'CY' => array_unique($CY),
										 'RTN' => array_unique($RTN),
										 'SalName' =>  array_unique($SalName),
										 'Numbook' => array_unique($Numbook),
										 'RTN' => array_unique($RTN),
										 'Loadingdate' => array_unique($Loadingdate),
										 'Type' => $Type,
										 'SubHC' => array_unique($SubHC),
										 'Booking_detail' => array_unique($Booking_detail),
										 'AGENT' => array_unique($AGENT)
									 ];
								 } else {
									 $fileFailed[] = $f;
								 }
							}else{

								 	$dataGet = $this->api->isBookingTFileMatchAx($dataso);
								 	$SOS = $dataGet["SO"];
									$PO = $dataGet["PO"];
									$PI = $dataGet["PI"];
									$CY = $dataGet["CY"];
									$RTN = $dataGet["RTN"];
									$SalName = $dataGet["SalName"];
									$Numbook = $dataGet["Numbook"];
									$Loadingdate = $dataGet["Loadingdate"];
									$SubHC = $dataGet["HC"];
									$Booking_detail = $dataGet["Booking_detail"];
									$AGENT = $dataGet["AGENT"];
									$Type = "New";
									if ($SOS !== "" ) {
										$fileOkay[] = [
											'file' => $f,
											'SO' => array_unique($SOS),
											'PO' => array_unique($PO),
											'PI' => array_unique($PI),
											'CY' => array_unique($CY),
											'RTN' => array_unique($RTN),
											'SalName' =>  array_unique($SalName),
											'Numbook' => array_unique($Numbook),
											'RTN' => array_unique($RTN),
											'Loadingdate' => array_unique($Loadingdate),
											'Type' => $Type,
											'SubHC' => array_unique($SubHC),
											'Booking_detail' => array_unique($Booking_detail),
											'AGENT' => array_unique($AGENT)
										];
									} else {
						 				$fileFailed[] = $f;
						 			}
								}

							}
					// echo "<pre>".print_r($fileOkay,true)."</pre>";
					// exit;
					if (count($fileOkay) !== 0) {
						foreach ($fileOkay as $data) {
							$goodData[] = [
								'file' => $data['file'],
								//'sender' => 'kanokporn_s@deestone.com',
								'sender' => 'kanokporn_s@deestone.com',
								//$automail->getEmailFromCustomerCode($data['customer']),
								'internal' => 'kanokporn_s@deestone.com',
					 			'name' => $data['SalName'],
					 			'SO' => $data['SO'],
					 			'PO' => $data['PO'],
					 			'Type' => $data['Type'],
					 			'Numbook' => $data["Numbook"],
								'PI' => $data["PI"],
								'Loadingdate' => $data["Loadingdate"],
								'RTN' => $data["RTN"],
								'CY' => $data["CY"],
								'SubHC' => $data["SubHC"],
								'Booking_detail' => $data["Booking_detail"],
								'AGENT' => $data["AGENT"]
							];
						}
						$txtSo = '';
						$txtPo = '';
						$txtPI = '';
						$txtLd = '';
						$txtCy = '';
						$txtRtn = '';
						$txtHc = '';
						$txtBk = '';
						$txtsub = '';
						$subject = '';
						foreach($goodData as $m) {
							if (count($m['SO'])>1) {
								for ($i=0; $i < count($m['SO']); $i++) {
									$txtSo .= $m['SO'][$i].",";
								}
								$txtSo = substr($txtSo,0,-1);
							}else{
								$txtSo .= $m['SO'][0];
							}
							if (count($m['PO'])>1) {
								for ($i=0; $i < count($m['PO']); $i++) {
									$txtPo .= $m['PO'][$i].",";
								}
								$txtPo = substr($txtPo,0,-1);
							}else{
								$txtPo .= $m['PO'][0];
							}
							if (count($m['PI'])>1) {
								for ($i=0; $i < count($m['PI']); $i++) {
									$txtPI .= $m['PI'][$i].",";
								}
								$txtPI = substr($txtPI,0,-1);
							}else{
								$txtPI .= $m['PI'][0];
							}
							if (count($m['Loadingdate'])>1) {
								for ($i=0; $i < count($m['Loadingdate']); $i++) {
									$txtLd .= $m['Loadingdate'][$i].",";
								}
								$txtLd = substr($txtLd,0,-1);
							}else{
								$txtLd .= $m['Loadingdate'][0];
							}
							if (count($m['CY'])>1) {
								for ($i=0; $i < count($m['CY']); $i++) {
									$txtCy .= $m['CY'][$i].",";
								}
								$txtCy = substr($txtCy,0,-1);
							}else{
								$txtCy .= $m['CY'][0];
							}
							if (count($m['RTN'])>1) {
								for ($i=0; $i < count($m['RTN']); $i++) {
									$txtRtn .= $m['RTN'][$i].",";
								}
								$txtRtn = substr($txtRtn,0,-1);
							}else{
								$txtRtn .= $m['RTN'][0];
							}
							if (count($m['SubHC'])>1) {
								for ($i=0; $i < count($m['SubHC']); $i++) {
									$txtHc .= $m['SubHC'][$i].",";
								}
									$txtHc = substr($txtHc,0,-1);
							}else{
								$txtHc .= $m['SubHC'][0];
							}
							if (count($m['Booking_detail'])>1) {
								for ($i=0; $i < count($m['Booking_detail']); $i++) {
									if($m['Booking_detail'][$i] == NULL){
										$txtBk .= $m['Booking_detail'][0];
									}else {
										$txtBk .= $m['Booking_detail'][$i].",";
									}

								}
								$txtBk = substr($txtBk,0,-1);
							}else{

								$txtBk .= $m['Booking_detail'][0];
							}
							$body = $this->api->getBookingBody_v3($txtSo, $txtPo, $txtPI, $txtLd, $txtCy, $txtRtn, $txtHc, $txtBk, $m['AGENT']);
							$subject = $this->api->getBookingSubject_internalAPI($txtSo, $m['name'], $txtPI, $m['Type'], $m['Numbook']);
							// echo "<pre>".print_r($getMail,true)."</pre>";
							// exit;
							$sendEmailInternal = $this->email->sendEmail(
								$subject,
								$body,
								$getMail['to'],
								$getMail['cc'],
								[],
								[$root . $m['file']],
								$getMail['sender'][0],
								$getMail['sender'][0]
							);
							if ($sendEmailInternal == true) {
								$logging = $this->automail->logging(
										$projectId,
										'Message has been sent',
										null,
										$txtSo,
										null,
										null,
										null,
										$m['file'],
										'File'
								);

								$this->automail->loggingEmail($logging,$getMail['to'],1);
								$this->automail->loggingEmail($logging,$getMail['cc'],2);
								echo "Message has been sent internal\n";
								$this->automail->initFolder($rootTemp, 'logs');
							 	$this->automail->moveFile($root, $rootTemp, 'logs/', $m['file']);
							}
							else{
								echo $sendEmailInternal;
								// sendfailed movefile
								$this->automail->initFolder($root, 'failed');
								$this->automail->moveFile($root, $root, 'failed/', $m['file']);
							}
							// echo "<pre>";
							//  print_r($getMail);
							//  echo "</pre>";

							$txtSo = '';
							$txtPo = '' ;
							$txtPI = '' ;
							$txtLd = '' ;
							$txtCy = '' ;
							$txtRtn = '' ;
							$txtHc = '' ;
							$txtBk = '' ;
							$txtsub = '';



					 }



				 }

			} catch (\Exception $e) {
				echo $e->getMessage();
			}
    }


}
