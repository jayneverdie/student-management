<?php

namespace App\Category;

use App\Common\Database;
use App\Common\Message;

class CategoryAPI
{
	public function __construct() {
		$this->db = Database::connect();
		$this->message = new Message;
	}

	public function all($filter) {
		return Database::rows(
			$this->db,
			"SELECT 
			C.id,
			C.category_name,
			S.status_name
			FROM web_category C
			LEFT JOIN web_status S ON S.id = C.category_status
			WHERE $filter"
		);
	}

	public function allActive() {
		return Database::rows(
			$this->db,
			"SELECT 
			id,
      category_name
			FROM web_category
			WHERE category_status = 1"
		);
	}

	public function update($name, $pk, $value, $table) {
    $update = Database::query(
      $this->db,
      "UPDATE $table
      SET $name = ?
      WHERE id = ?",
      [
        $value,
        $pk
      ]
    );

    if ( $update ) {
      	return $this->message->result(true, 'Update successful!');
    } else {
      	return $this->message->result(false, 'Update failed!');
    }
	}
	
	public function create($category_name) {
		$create = Database::query(
			$this->db,
			"INSERT INTO web_category(category_name)
			VALUES(?)",
			[
				$category_name
			]
		);

		if ($create) {
			return $this->message->result(true, 'Create successful!');
		} else {
			return $this->message->result(false, 'Create failed!');
		}
	}
}