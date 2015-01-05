<?php
class Endpoint_Entity {

	private $name;
	private $dbTable;
	private $file;
	public $model;
	public $load;
	public $factoryOutputCalled = false;
	public $urisCalled = array();
	public $activeParams = array();
	private $outputFormat = 'json';
	private $callback;
	private $skeleton;
	private $libraries = array();
	private $models = array();

	public function __construct($endpointName, $callback, Skeleton $skeleton) {
		$this->name = $endpointName;
		$this->file = $this->_getEndpointFile();
		$this->callback = $callback;
		$this->skeleton = $skeleton;
		$this->load = new Skeleton_Load($this);
		// Check if endpoint name has a table match
		// @todo
	}

	public function &__get($param) {
		if(array_key_exists($param, $this->activeParams)) {
			return $this->activeParams[$param];
		}
		if(property_exists($this, $param)) {
			return $this->{$param};
		}
		return false;
	}

	public function __destruct() {
		if(!$this->factoryOutputCalled && is_callable($this->callback)) {
			// run the users callback
			call_user_func($this->callback, $this, $this->skeleton, $this->skeleton->request);
		}
	}

	private function _getEndpointFile() {
		$trace = debug_backtrace();
		return strtolower(str_replace(array(EXT, SERVICE_PATH . 'endpoints'), '', basename($trace[2]['file'])));
	}

	public function get($uri, $callback = null) {
		$madeUri = $this->_makeUri($uri);
		$this->skeleton->router->addToMap($madeUri, $this->file);
		$this->_makeParams($uri);
		if($this->skeleton->router->getPreloadFlag() == true) return;
		if($this->skeleton->request->method() != 'GET') return;
		// make sure URI is valid
		if(!$this->skeleton->router->routeMatch($madeUri, $this->skeleton->router->getUri())) return;
		// make sure its not a duplicate request
		if(in_array($madeUri, $this->urisCalled)) return;
		// continue!
		$this->urisCalled[] = str_replace($this->skeleton->router->getRoutePatternKeys(), ':any', $madeUri);
		if(is_array($uri)) {
			return $callback($this);
		}
		if(is_callable($uri)) {
			return $uri($this, $this->skeleton, $this->skeleton->request);
		}
		return $callback($this, $this->skeleton, $this->skeleton->request);
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

	private function _makeParams($requestParams = array()) {
		$segments = $this->skeleton->router->getUriSegments();
		if(is_string($requestParams)) {
			$requestParams = explode('/', $requestParams);
			foreach($requestParams as $index => $param) {
				if(is_string($param) && strpos($param, ':') !== false) {
					$param = ($index == 0) ? str_replace(':', '', $param) : str_replace(':', '', $param.$index);
					$requestParams[$index] = $param;
				}
			}
		}
		$params = array();
		$i = 1;
		foreach($requestParams as $param => $val) {
			if(is_numeric($param)) {
				// no default value set
				$params[$val] = (isset($segments[$i])) ? $segments[$i] : null;
			} else {
				// default value set
				$params[$param] = (isset($segments[$i])) ? $segments[$i] : $val;
			}
			$i++;
		}
		$this->activeParams = $params;
		return $params;
	}

	private function _makeUri($uri = null) {
		$uriString = $this->name;
		if(is_array($uri)) {
			foreach($uri as $p) {
				if(is_string($p) && strlen($p) > 0) {
					$uriString .= '/:any';
				}
			}
		} elseif (is_callable($uri)) {
			// do nothing
		} elseif (is_string($uri)) {
			$uriString .= '/' . $uri;
		}
		return $uriString;
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