<?php

namespace App\Booking;

use App\Common\View;
use App\Booking\BookingAPI;
use App\Common\Automail;
use App\Email\EmailAPI;
use App\Common\Datatables;

class BookingController {

	public function __construct() {
		$this->view = new View;
		$this->booking = new BookingAPI;
		$this->automail = new Automail;
		$this->email = new EmailAPI;
		$this->datatables = new Datatables;
	}

	public function send($request, $response, $args) {
		try {

			$projectId = 10;
			$root = 'files/booking_confirmation/all/';
			$filesOkay = [];
			$fileFailed = [];

			$this->automail->initFolder($root);

			$files = $this->automail->getDirRoot($root);

			foreach ($files as $file) {

				$so = $this->automail->getSOFromFileBooking($file);
				$customer = $this->automail->getCustomerCode($file);

				if ($this->automail->isBookingRevise($file) === false) {

					if (isset($so) && $this->booking->isSOAndCustomerMatched($so, $customer) === true) {
						$customerCode = $this->automail->getCustomerCode($file);
						$fileOkay[] = [
							'customer' => $customerCode,
							'file' => $file
						];
					} else {
						$fileFailed[] = $file;
					}


				}

			}

			if (count($fileOkay) !== 0) {

				foreach ($fileOkay as $data) {

					// $email = $this->automail->getCustomerMail($data['customer']);
					$email = ['to' => ['harit_j@deestone.com'], 'cc' => []];

					$success[] = [
						'customer' => $data['customer'],
						'email' => $email,
						'file' => $data['file'],
						'sender' => $this->automail->getEmailFromCustomerCode($data['customer']),
						// 'internal' => 'kanokporn_s@deestone.com'
						'internal' => 'harit_j@deestone.com'
					];
				}

				// echo "<pre>".print_r($goodData,true)."</pre>";
				foreach ($success as $m) {

					echo $this->booking->getBookingSubject_v2($m['file']);
					echo "<br>";
					echo $this->booking->getBookingBody_v2($m['file']);
					echo "<br>";
					echo "<pre>".print_r($m['email']['to'],true)."</pre>";
					echo "<br>";
					echo "<pre>".print_r($m['sender'],true)."</pre>";

				}

			}

			if (count($fileFailed) > 0) {

				echo $this->booking->getSubjectReportFailed();
				echo "<br>";
				echo $this->booking->getBodyReportFailed($fileFailed, 'New Booking confirmation', 'ชื่อไฟล์ไม่ถูกต้อง');
				// echo "<br>";
				// echo "<pre>".print_r($m['email']['to'],true)."</pre>";
				// echo "<br>";
				// echo "<pre>".print_r($m['sender'],true)."</pre>";

			}

		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}

}
