<?php
class Endpoint_Entity {

	public $name;
	public $dbTable;
	public $model;
	public $load;
	public $factoryOutputCalled = false;
	private $outputFormat = 'json';
	private $callback;
	private $skeleton;
	private $libraries;
	private $models;

	public function __construct($endpointName, $callback, Skeleton $skeleton) {
		$this->callback = $callback;
		$this->skeleton = $skeleton;
		$this->load = new Skeleton_Load($this);
		// Check if endpoint name has a table match
		// @todo
	}

	public function __destruct() {
		if(!$this->factoryOutputCalled && is_callable($this->callback)) {
			// run the users callback
			call_user_func($this->callback, $this->skeleton, $this, $this->skeleton->request);
		}
	}

	public function get($uri, $callback = null) {
		if($this->skeleton->request->method() != 'GET') return;
		if(is_callable($uri)) {
			return $uri($this->skeleton, $this, $this->skeleton->request);
		}
		return $callback($this->skeleton, $this, $this->skeleton->request);
	}

	public function post($uri, $callback = null) {
		if($this->skeleton->request->method() != 'POST') return;
		if(is_callable($uri)) {
			return $uri($this->skeleton, $this, $this->skeleton->request);
		}
		return $callback($this->skeleton, $this, $this->skeleton->request);
	}

	public function put($uri, $callback = null) {
		if($this->skeleton->request->method() != 'PUT') return;
		if(is_callable($uri)) {
			return $uri($this->skeleton, $this, $this->skeleton->request);
		}
		return $callback($this->skeleton, $this, $this->skeleton->request);
	}

	public function delete($uri, $callback = null) {
		if($this->skeleton->request->method() != 'DELETE') return;
		if(is_callable($uri)) {
			return $uri($this->skeleton, $this, $this->skeleton->request);
		}
		return $callback($this->skeleton, $this, $this->skeleton->request);
	}

	public function setModel($tableName = null) {
		// $this->model = new Skeleton_Model()
	}

	public function setTable($tableName) {
		$this->dbTable = $tableName;
		if($this->model == null) {
			// create dynamic model with table
			$this->model = new Skeleton_Model($this->dbTable);
		}
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

	public function getAll() {
		$all = R::findAll($this->dbTable);
		$this->out(R::exportAll($all));
		return $all;
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