<?php
class User_Model extends Skeleton_Model {

	public function __construct() {
		parent::__construct();
		$this->table = 'users';
	}

}