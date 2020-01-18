<?php

namespace App\Shipping;

use App\Common\View;
use App\Shipping\ShippingAPI;
use App\Common\Automail;
use App\Email\EmailAPI;
use App\Common\Datatables;

class ShippingController {

	public function __construct() {
		$this->view = new View;
		$this->shipping = new ShippingAPI;
		$this->automail = new Automail;
		$this->email = new EmailAPI;
		$this->datatables = new Datatables;
	}

	public function all($request, $response, $args) {
		return $this->view->render('pages/shipping/all');
	}

	public function allSend($request, $response, $args) {
		try {

			$projectId = 5;
			$root = 'files/shipping_document/all/';
			$filesOkay = [];
			$fileFailed = [];

			$this->automail->initFolder($root);

			$files = $this->automail->getDirRoot($root);

			foreach ($files as $file) {
				$customerCode = $this->automail->getCustomerCode($file);
				$quantation = $this->automail->getQuantationArray($file);
				$qaConverted = $this->automail->convertArrayToInSQL($quantation);
				$invoice = $this->automail->getInvoice($file);
				$invoiceNumber = substr($invoice,3,8);
				
				if ($this->shipping->isSurrender($file) === false) {
					if ($this->shipping->mapQuantationManyQA($customerCode, $qaConverted, $invoiceNumber) === true) {
						$filesOkay[] = [
							'customer' => $customerCode,
							'file' => $file
						];
					} else {
						$fileFailed[] = $file;
					}
				} 
			}
			
			if (count($filesOkay) > 0) {
				foreach ($filesOkay as $f) {
					// $email = $this->automail->getCustomerMail($f['customer']);
					$email = ['to' => ['harit_j@deestone.com'], 'cc' => []];
					// $acc_fin = ['to' => ['shippingdoc@deestone.com'], 'cc' => []];
					$acc_fin = ['to' => ['harit_j@deestone.com'], 'cc' => []];

					$success[] = [
						'customer' => $f['customer'],
						'email' => $email,
						'file' => $f['file'],
						'sender' => $this->automail->getEmailFromCustomerCode($f['customer']),
						'internal' => 'shippingdoc@deestone.com',
						'acc_fin' => $acc_fin
					];
				}

				foreach ($success as $m) {
					if( $this->shipping->getShippingBody($m['file']) !== false ) {

						$this->email->sendEmail(
							$this->shipping->getShippingSubject($m['file']), 
							$this->shipping->getShippingBody($m['file']), 
							$email['to'], 
							[], 
							[], 
							[$root . $m['file']],
							$m['sender'],
							$m['sender']
						);

						echo 'send file: ' . $m['file'] . ' success.<br/>';

						// $this->automail->logging(
						// 	$projectId,
						// 	'Message has been sent',
						// 	$m['customer'],
						// 	null,
						// 	null,
						// 	null,
						// 	null,
						// 	$m['file'],
						// 	'file'
						// );

						$checkDocsInFileName = $this->shipping->isDocsInFileName($m['file']);

						if ( $checkDocsInFileName ===  true ) {
							// send to account
							$this->email->sendEmail(
								'Send to Account Test: ' . $this->shipping->getShippingSubject($m['file']), 
								$this->shipping->getShippingBody($m['file']), 
								$acc_fin['to'], 
								[], 
								[], 
								[$root . $m['file']],
								$m['sender'],
								$m['sender']
							);

						}

						$this->automail->moveFile($root, 'temp/', $m['file']);
					}
				}
			}
			
			if (count($fileFailed) > 0) {

				$this->email->sendEmail(
					$this->automail->getSubjectReportFailed(), 
					$this->automail->getBodyReportFailed($fileFailed, 'Shipping document', 'ไฟล์ไม่ถูกต้อง'), 
					$this->shipping->getMailFailed(), 
					[], 
					[], 
					$this->automail->updateFilePath($root, $fileFailed),
					'ea_devteam@deestone.com',
					'ea_devteam@deestone.com'
				);

				foreach ($fileFailed as $f) {
					echo 'send file: ' . $f . ' failed.<br/>';
					$this->automail->moveFile($root, 'failed/', $f);
					$this->automail->logging(
						$projectId,
						'ไฟล์ไม่ถูกต้อง',
						null,
						null,
						null,
						null,
						null,
						$f,
						'file'
					);
				}
			}

		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}

	public function sendConfirm($request, $response, $args) {
		try {

			$projectId = 5;
			$root = 'files/shipping_document/doc_please_confirm/';
			$filesOkay = [];
			$fileFailed = [];

			$this->automail->initFolder($root);

			$files = $this->automail->getDirRoot($root);

			foreach ($files as $file) {
				$customerCode = $this->automail->getCustomerCode($file);
				$quantation = $this->automail->getQuantationArray($file);
				$qaConverted = $this->automail->convertArrayToInSQL($quantation);
				$invoice = $this->automail->getInvoice($file);
				$invoiceNumber = substr($invoice,3,8);
				
				if ($this->shipping->isSurrender($file) === false) {
					if ($this->shipping->mapQuantationManyQA($customerCode, $qaConverted, $invoiceNumber) === true) {
						$filesOkay[] = [
							'customer' => $customerCode,
							'file' => $file
						];
					} else {
						$fileFailed[] = $file;
					}
				} 
			}
			
			if (count($filesOkay) > 0) {
				foreach ($filesOkay as $f) {
					// $email = $this->automail->getCustomerMail($f['customer']);
					$email = ['to' => ['harit_j@deestone.com'], 'cc' => ['saba','jeam']];

					$success[] = [
						'customer' => $f['customer'],
						'email' => $email,
						'file' => $f['file'],
						'sender' => $this->automail->getEmailFromCustomerCode($f['customer']),
						'internal' => 'shippingdoc@deestone.com'
					];
				}
				
				// echo "<pre>".print_r($email,true)."</pre>";
				// print_r($email);
				// exit();

				foreach ($success as $m) {
					if( $this->shipping->getShippingConfirmBody($m['file']) !== false ) {
						
						// echo $this->shipping->getShippingConfirmSubject($m['file']);
						// echo "<br>";
						// echo $this->shipping->getShippingConfirmBody($m['file']);
						// echo "<br>";
						// print_r($email['to']);
						// echo "<br>";
						// echo $root . $m['file'];
						// echo "<br>";
						// echo $m['sender'];
						
						$this->email->sendEmail(
							$this->shipping->getShippingConfirmSubject($m['file']), 
							$this->shipping->getShippingConfirmBody($m['file']), 
							$email['to'], 
							[], 
							[], 
							[$root . $m['file']],
							$m['sender'],
							$m['sender']
						);
						
						echo 'send file: ' . $m['file'] . ' success.<br/>';
						$this->automail->moveFile($root, 'temp/', $m['file']);
						
						// $this->automail->logging(
						// 	$projectId,
						// 	'Message has been sent',
						// 	$m['customer'],
						// 	null,
						// 	null,
						// 	null,
						// 	null,
						// 	$m['file'],
						// 	'file'
						// );

					}
				}
			}

			// exit();

			if (count($fileFailed) > 0) {

				$this->email->sendEmail(
					$this->automail->getSubjectReportFailed(), 
					$this->automail->getBodyReportFailed($fileFailed, 'Shipping document', 'ไฟล์ไม่ถูกต้อง'), 
					$this->shipping->getMailFailed(), 
					[], 
					[], 
					$this->automail->updateFilePath($root, $fileFailed),
					'ea_devteam@deestone.com',
					'ea_devteam@deestone.com'
				);

				foreach ($fileFailed as $f) {
					echo 'send file: ' . $f . ' failed.<br/>';
					$this->automail->moveFile($root, 'failed/', $f);
					$this->automail->logging(
						$projectId,
						'ไฟล์ไม่ถูกต้อง',
						null,
						null,
						null,
						null,
						null,
						$f,
						'file'
					);

				}
			}
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}

	public function sendAot($request, $response, $args) {
		try {

			$projectId = 3;
			$root = 'files/shipping_document/aot/';
			$fileLists = [];
			$allFiles = [];
			$fileIncorrect = [];
			$fileIncorrectFormat = [];

			$this->automail->initFolder($root);
			$files = $this->automail->getDirRoot($root);
			
			foreach ($files as $file) {
				if (gettype($file) !== 'array') {
					if ($file !== 'Thumbs.db') {
						$allFiles[] = [
							'file_name' => $file,
							'file_size' => $this->automail->Size($root . $file)
						];
					}
				}
			}

			$tmp_file = [];
			$count_file_size = 0;
			$counter_tmp_file = 0;

			foreach ($allFiles as $value) {

				if (substr($value['file_name'], 0, 1) !== '_') {

					$typeOfFileSize = explode(' ', $value['file_size']);

					if ($typeOfFileSize[1] === 'KB') {
						$tmp_file_size = $typeOfFileSize[0] * 0.001;
					} else {
						$tmp_file_size = $typeOfFileSize[0];
					}

					$count_file_size += $tmp_file_size;

					if (round($count_file_size, 2) <= 10.00) { // 10 MB
						$tmp_file[$counter_tmp_file][] = $value['file_name'];
					} else  {
						$count_file_size = 0;
						$counter_tmp_file++;
						$tmp_file[$counter_tmp_file][] = $value['file_name'];
					}
				} else {
					$fileIncorrectFormat[] = $value['file_name'];
				}
			}

			$email = [
				'to' => ['harit_j@deestone.com'],
				'cc' => ['wattana_r@deestone.com','worawut_s@deestone.com','harit_j@deestone.com'],
				'sender' => ['harit_j@deestone.com']
			];

			// echo count($tmp_file);
			// exit();

			for ($i=0; $i < count($tmp_file); $i++) { 

				if (count($tmp_file[$i]) === 0) {
					exit('Folder is empty.' . PHP_EOL);
				}

				$array_file = [];
				foreach ($tmp_file[$i] as $f) {
					array_push($array_file, $root.$f);
				}

				// echo "<pre>".print_r($array_file,true)."</pre>";
				
				// Send To External
				$this->email->sendEmail(
					$this->shipping->getAOTSubject($tmp_file[$i]), 
					$this->shipping->getAOTBody(), 
					$email['to'], 
					[], 
					[], 
					$array_file,
					$email['sender'][0],
					$email['sender'][0]
				);

				// Send To Internal
				$this->email->sendEmail(
					$this->shipping->getAOTSubject($tmp_file[$i]), 
					$this->shipping->getAOTBody(), 
					$email['cc'], 
					[], 
					[], 
					$array_file,
					$email['sender'][0],
					$email['sender'][0]
				);

				echo 'send file : success.<br/>';
	
				$checkDocsInFileName = $this->shipping->isDocsAOTInFileName($tmp_file[$i]);
				// echo count($checkDocsInFileName);
				if ( count($checkDocsInFileName) !== 0 ) {
					// send to account
					// $this->email->sendEmail(
					// 	'Send to Account Test: ' . $this->shipping->getShippingSubject($m['file']), 
					// 	$this->shipping->getShippingBody($m['file']), 
					// 	$acc_fin['to'], 
					// 	[], 
					// 	[], 
					// 	[$root . $m['file']],
					// 	$m['sender'],
					// 	$m['sender']
					// );

				}

				foreach ($tmp_file[$i] as $file) {
					$this->automail->moveFile($root, 'temp/', $file);
				}

			}
			
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}

	public function getLogs($request, $response, $args) {
		try {
			$parsedBody = $request->getParsedBody();
			$data = $this->shipping->getLogs($this->datatables->filter($parsedBody));
			$pack = $this->datatables->get($data, $parsedBody);
		
			return $response->withJson($pack);
		} catch (\Exception $e) {
			return ['error' => $e->getMessage()];
		}
	}
}