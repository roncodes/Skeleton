<?php
class JSON {

	public function __construct() {}

	public static function out($data) {
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}