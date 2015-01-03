<?php
class Endpoint_Entity {

	public $name;
	public $dbTable;
	public $factoryOutputCalled = false;
	private $outputFormat = 'json';
	private $callback;
	private $skeleton;

	public function __construct($endpointName, $callback, Skeleton $skeleton) {
		$this->callback = $callback;
		$this->skeleton = $skeleton;
	}

	public function __destruct() {
		if(!$this->factoryOutputCalled) {
			// run the users callback
			call_user_func($this->callback, $this->skeleton, $this, $this->skeleton->request);
		}
	}

	public function get() {

	}

	public function post() {

	}

	public function put() {

	}

	public function delete() {

	}

	public function setTable($tableName) {
		$this->dbTable = $tableName;
		return $this;
	}

	public function factoryOutput() {
		// run default output -- GET, POST, PUT, DELETE Factory stuff
		$this->factoryOutputCalled = true;
		if($this->skeleton->request->method() == 'GET') {
			$all = R::findAll($this->dbTable);
			$this->out(R::exportAll($all));
		}
		return $this;
	}

	public function out($data) {
		switch ($this->outputFormat) {
			case 'json':
				JSON::out($data);
				break;	
			case 'xml':
				XML::out($data);
				break;	
			default:
				JSON::out($data);
				break;
		}
	}

	public function outputAs($format = 'json') {
		$format = strtolower($format);
		switch ($format) {
			case 'json':
				$this->outputFormat = 'json';
				break;
			case 'xml':
				$this->outputFormat = 'xml';
				break;
			case 'csv':
				$this->outputFormat = 'csv';
				break;
			default:
				$this->outputFormat = 'json';
				break;
		}
		return $this;
	}

}