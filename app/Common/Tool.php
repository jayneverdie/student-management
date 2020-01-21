<?php

namespace App\Common;

use App\Common\Tool;

class Tool {

	public function getDirRoot($path) {
		try {
			$rootDir = self::dirToArray($path);

			$files = [];

			foreach ($rootDir as $f) {
				if (\gettype($f) !== 'array') {
					if ($f !== 'Thumbs.db') {
						$files[] = $f;
					}
				}
			}
			return $files;
		} catch (\Exception $e) {
			throw new \Exception('Error: cannot get root path.');
		}
	}

	public function dirToArray($dir) {
		try {
			$result = array();
			$cdir = \scandir($dir);
			foreach ($cdir as $key => $value) {
				if (!\in_array($value,array(".",".."))) {
					if (\is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
						$result[$value] = self::dirToArray($dir . DIRECTORY_SEPARATOR . $value);
					} else {
						$result[] = $value;
					}
				}
			}
			return $result;
		} catch (\Exception $e) {
			throw new \Exception('Error: cannot get dir path.');
		}
	}

	public function convertArrayToInSQL($arr) {
		try {
			$txt = '';
			if(is_array($arr)) {
				foreach ($arr as $v) {
					$txt .= '\'' . $v . '\',';
				}
				return trim($txt, ',');
			} else {
				return '';
			}
		} catch (\Exception $e) {
			return '';
		}
	}

	public function Size($path)
	{
	    $bytes = sprintf('%u', filesize($path));
	    
	    if ($bytes > 0)
	    {
	      $unit = intval(log($bytes, 1024));
	      $units = array('B', 'KB', 'MB', 'GB');

	      if (array_key_exists($unit, $units) === true)
	      {
	        return sprintf('%.2f %s', $bytes / pow(1024, $unit), $units[$unit]);
	      }
	    }
	    
	    return $bytes;
	}

	public function initFolder($root, $folder) {
		try {
			$dateFolder = date('Y') . date('m');

			if (!file_exists($root . '/'.$folder.'/')) {
				mkdir($root . '/'.$folder.'/', 0777, true);
			}

			return "Create folder success.\n";
		} catch (\Exception $e) {
			throw new \Exception('Error: create folder failed.');
		}
	}

	public function readCard() {
		try {
			$arrContextOptions=array(
			    "ssl"=>array(
			        "verify_peer"=>false,
			        "verify_peer_name"=>false,
			    ),
			);
			$url_card = "https://localhost:8443/smartcard/data/";
			$url_img = "https://localhost:8443/smartcard/picture/";
		    $response = file_get_contents($url_card, false, stream_context_create($arrContextOptions));
		    
		    if (json_decode($response, true)) {

		    	$root = 'files/images/parent/';
		    	self::initFolder($root, json_decode($response)->cid);
		    	$img = json_decode($response)->cid.".jpg";
			    file_put_contents($root."/".json_decode($response)->cid."/".$img, file_get_contents($url_img, false, stream_context_create($arrContextOptions))); 
		    	return $response;

		    }else{
		    	return json_encode([
		    		"result" => false,
		    		"message" => "Can't read Card!."
		    	]);
		    }	
			
		} catch (\Exception $e) {
			throw new \Exception('Error: read card failed.');
		}
	}

}
