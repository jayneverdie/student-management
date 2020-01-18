<?php
namespace App\Common;

use Wattanar\Sqlsrv;

class Database {

	public static $instance;
	public static $instance_ax;

	public static function default() {
		if (!isset(Database::$instance)) {
			Database::$instance = Sqlsrv::connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			return Database::$instance;
		}
		return Database::$instance;
	}

	public static function ax() {
		if (!isset(Database::$instance_ax)) {
			Database::$instance_ax = Sqlsrv::connect(DB_AX_HOST, DB_AX_USER, DB_AX_PASS, DB_AX_NAME);
			return Database::$instance_ax;
		}
		return Database::$instance_ax;
	}

	public static function connect($db = null) {
		try {
			if ( $db === null ) {
				return Database::default();
			} else if ( $db === 'ax' ) {
				return Database::ax();
			}
		} catch (\Exception $e) {
			return ['error' => $e->getMessage()];
		}
	}

	public static function rows($conn, $query, $params = null) {
		try {
			return Sqlsrv::rows($conn, $query, $params);
		} catch (\Exception $e) {
			return ['error' => $e->getMessage()];
		}
	}

	public static function query($conn, $query, $params = null) {
		try {
			return Sqlsrv::query($conn, $query, $params);
		} catch (\Exception $e) {
			return ['error' => $e->getMessage()];
		}
	}

	public static function hasRows($conn, $query, $params = null) {
		try {
			return Sqlsrv::hasRows($conn, $query, $params);
		} catch (\Exception $e) {
			return ['error' => $e->getMessage()];
		}
	}
}