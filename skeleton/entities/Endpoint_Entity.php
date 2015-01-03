<?php
class Endpoint_Entity {

	public $name;
	public $dbTable;
	public $outputCalled = false;
	private $callback;
	private $skeleton;

	public function __construct($endpointName, $callback, Skeleton $skeleton) {
		$this->callback = $callback;
		$this->skeleton = $skeleton;
	}

	public function __destruct() {
		if(!$this->outputCalled) {
			// run the users callback
			call_user_func($this->callback, $this->skeleton, $this);
		}
	}

	public function setTable($tableName) {
		$this->dbTable = $tableName;
		return $this;
	}

	public function output() {
		// run default output -- GET, POST, PUT, DELETE Factory stuff
		$this->outputCalled = true;
		echo json_encode(array('status' => 'success'));
	}

}