<?php

namespace App\Camso;

use App\Common\View;
use App\Camso\CamsoAPI;
use App\Common\Automail;
use App\Email\EmailAPI;
use App\Common\Datatables;

class CamsoController {

	public function __construct() {
		$this->view = new View;
		$this->camso = new CamsoAPI;
		$this->automail = new Automail;
		$this->email = new EmailAPI;
		$this->datatables = new Datatables;
	}

	public function runCamso($request, $response, $args) {
		try {
			
			$email_dev = ['to' => ['harit_j@deestone.com'], 'cc' => ['wattana_r@deestone.com']];
			$email_internal_dev = ['to' => ['wattana_r@deestone.com'], 'cc' => []];
			$acc_fin_dev = ['to' => ['harit_j@deestone.com'], 'cc' => []];

			$acc_fin = [
				'to' => [	
						"alintheeta_w@deestone.com",
					    "pramate_p@deestone.com",
					    "boonreon_r@deestone.com",
					    "jaruphan_i@deestone.com",
					    "kannika_w@deestone.com",
					    "jirapat_p@deestone.com",
					    "salinee_p@deestone.com",
					    "kritsana_p@deestone.com"
					]
			];

			$parsedBody = $request->getParsedBody();
			$params = $request->getQueryParams();
			$custcode = $parsedBody["custcode"];
			// exit();
			$stackFileByCustCode = [];
			$stackFileFailed = [];
			$stackCamsoLoadstar_FAIL = [];
			$listOfMailDetail = [];
			$mailData = [];

			if ($custcode === "C-1441") {
				$projectId = 7;
				$_custcode = "C-1441";
				$root = 'files/camso/C-1441/';
				$rootTemp = 'temp/camso/C-1441/';
				$_subjectMailCust = 'CAMSO LOADSTAR (PVT) LTD (PV3541)';
			} else if ($custcode === "C-2536") {
				$projectId = 8;
				$_custcode = "C-2536";
				$root = 'files/camso/C-2536/';
				$rootTemp = 'temp/camso/C-2536/';
				$_subjectMailCust = 'CAMSO TRADING (Private) LIMITED';
			}else if ($custcode === "ISF"){
				$projectId = 9;
				$_custcode = "C-2536";
				$root = 'files/camso/ISF/';
				$rootTemp = 'temp/camso/ISF/';
				$_subjectMailCust = 'CAMSO TRADING (Private) LIMITED';
			}

			$files = $this->automail->getDirRoot($root);

			if (count($files)===0) {
				echo "The file does not exist";
				exit();
			}
			
			foreach ($files as $f) {
				
				if ($this->camso->checkFileExist($f,$projectId)===true) {
				
					$this->automail->initFolder($rootTemp, 'exists');
					$this->automail->moveFile($root, $rootTemp, 'exists/', $f);

				}else{

					$dataFromFileName = $this->camso->getDataFromInvoice($f);
					$quantationCamso  = $this->camso->getCamsoFullQuantation($f); 

					// echo "<pre>".print_r($quantationCamso,true)."</pre>";

					if ($custcode !== 'C-1441') 
					{
						$dataFromFileName['DSG_SHIPPINGMARK'] = '';
					}

					if ($custcode === 'C-2536') 
					{
						$_shippingMark = $this->camso->newLineShippingMark($dataFromFileName['DSG_SHIPPINGMARK']);
					}
					else
					{
						$_shippingMark = $dataFromFileName['DSG_SHIPPINGMARK'];
					}

					$state = $this->camso->getStateAndPort($this->camso->getQaNoSharp($quantationCamso), $_custcode);
				
					if (count($state) !== 0) {
						if (count($dataFromFileName) !== 0 && $this->camso->getCustomerCode($f) === $_custcode && $this->camso->camsoStateActive($state[0]['DSG_STATE'], $_custcode) === true) {
							
							$mailCustomer = $this->camso->getMailCustomer($projectId,$state[0]['DSG_STATE'],$state[0]['Port']);
							// echo "<pre>".print_r($state,true)."</pre>";
							// echo "<pre>".print_r($mailCustomer,true)."</pre>";

							$mailData[] = [
								'file' => [$root.$f],
								'filename' => [$f],
								'quantation' => $quantationCamso,
								'mail_to' => $mailCustomer['to'],
								'mail_cc' => $mailCustomer['cc'],
								'mail_to_internal' => $mailCustomer['internal'],
								'mail_sender' => $mailCustomer['sender'],
								'data' => [
									'invoice' => strtoupper($dataFromFileName['DATAAREAID']) . '/' . $dataFromFileName['DSG_VOUCHERSERIES'] . '/' . $dataFromFileName['DSG_VOUCHERNO'],
									'lc_no' => $dataFromFileName['DSG_LC_NO'],
									'issue_bank' => $dataFromFileName['DSG_ISSUEDBANK'],
									'shipping_mark' => $_shippingMark,
									'cust_ref' => $dataFromFileName['CUSTOMERREF'],
									'cust_name' => $_subjectMailCust,
									'issue_date' => $dataFromFileName['DSG_LC_DATE1'],
									'country' => $state[0]['Country'],
									'state' => $state[0]['DSG_STATE'],
									'port' => $state[0]['Port']
								]
							];

							// echo "<pre>".print_r($mailData,true)."</pre>";
						}else{
							$stackCamsoLoadstar_FAIL[] = $f;
						}
					}

				}
			}

			
			if (count($mailData) !== 0) {
				
				foreach ($mailData as $value) {

					// send to external
					$sendEmail = $this->email->sendEmail(
						$this->camso->getCamsoSubject($value['quantation'], $value['data']['cust_name'], $custcode),
						$this->camso->getCamsoBody(
							$value['data']['cust_ref'], 
							$value['quantation'], 
							$value['data']["invoice"], 
							$value['data']["lc_no"], 
							str_replace('\n', '<br>', $value['data']["shipping_mark"]), 
							$value['data']["issue_bank"],
							$value['data']["issue_date"],
							$value['data']['cust_name'],
							$custcode
						),
						$value['mail_to'], 
						$value['mail_cc'], 
						// $email_dev['to'],
						// $email_dev['cc'],
						[], 
						[$root . $value['filename'][0]],
						$value['mail_sender'][0],
						$value['mail_sender'][0]
					);

					if($sendEmail === true) {
						echo "Message has been sent External\n";
						
						// insert logs
						$logging = $this->automail->logging(
							$projectId,
							'Message has been sent',
							$custcode,
							null,
							null,
							$value['quantation'],
							$value['data']["invoice"],
							$value['filename'][0],
							'file'
						);

						$this->automail->loggingEmail($logging,$value['mail_to'],1);
						$this->automail->loggingEmail($logging,$value['mail_cc'],2);

						// send to internal
						$sendEmailInternal = $this->email->sendEmail(
							$this->camso->getCamsoSubject($value['quantation'], $value['data']['cust_name'], $custcode),
							$this->camso->getCamsoBody(
								$value['data']['cust_ref'], 
								$value['quantation'], 
								$value['data']["invoice"], 
								$value['data']["lc_no"], 
								str_replace('\n', '<br>', $value['data']["shipping_mark"]), 
								$value['data']["issue_bank"],
								$value['data']["issue_date"],
								$value['data']['cust_name'],
								$custcode
							),
							$value['mail_to_internal'], 
							// $email_internal_dev['to'],
							[], 
							[], 
							[$root . $value['filename'][0]],
							$value['mail_sender'][0],
							$value['mail_sender'][0]
						);

						if($sendEmailInternal === true) {
							echo "Message has been sent internal\n";
						}else{
							echo $sendEmailInternal;
						}

						$checkDocsInFileName = $this->camso->isDocsInFileName($value['filename']);

						if (count($checkDocsInFileName) !== 0) {
							// send to acc fin
							$sendEmailAcc = $this->email->sendEmail(
								$this->camso->getCamsoSubject($value['quantation'], $value['data']['cust_name'], $custcode),
								$this->camso->getCamsoBody(
									$value['data']['cust_ref'], 
									$value['quantation'], 
									$value['data']["invoice"], 
									$value['data']["lc_no"], 
									str_replace('\n', '<br>', $value['data']["shipping_mark"]), 
									$value['data']["issue_bank"],
									$value['data']["issue_date"],
									$value['data']['cust_name'],
									$custcode
								),
								$acc_fin['to'], 
								// $acc_fin_dev['to'],
								[], 
								[], 
								[$root . $value['filename'][0]],
								$value['mail_sender'][0],
								$value['mail_sender'][0]
							);
							
							if($sendEmailAcc === true) {
								echo "Message has been sent Acc\n";
							}else{
								echo $sendEmailAcc;
							}
						}

						// sendSucess movefile
						$this->automail->initFolder($rootTemp, 'logs');
						$this->automail->moveFile($root, $rootTemp, 'logs/', $value['filename'][0]);

					}else{
						echo $sendEmail;
						// sendfailed movefile
						$this->automail->initFolder($root, 'failed');
						$this->automail->moveFile($root, $root, 'failed/', $value['filename'][0]);
					}
					
				}
				
			}

		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}

}