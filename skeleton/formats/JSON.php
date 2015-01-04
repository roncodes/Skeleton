<?php
class JSON {

	public function __construct() {}

	public static function out($data, $val = null) {
		header('Content-Type: application/json');
		if($val !== null) {
			// output as key value
			echo json_encode(array($data => $val));
		} else {
			echo json_encode($data);
		}
	}
}