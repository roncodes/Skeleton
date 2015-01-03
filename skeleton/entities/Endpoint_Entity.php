<?php
class Endpoint_Entity {

	public $name;
	public $dbTable;
	private $callback;
	private $skeleton;

	public function __construct($endpointName, $callback, Skeleton $skeleton) {
		$this->callback = $callback;
		$this->skeleton = $skeleton;
	}

	public function __destruct() {
		call_user_func($this->callback, $this->skeleton, $this);
	}

	public function setTable($tableName) {
		$this->dbTable = $tableName;
	}

}