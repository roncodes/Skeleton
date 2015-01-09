<?php
class Endpoint_Entity {

	public $name;
	public $dud = null;
	private $dbTable;
	private $file;
	public $model;
	public $load;
	public $factoryOutputCalled = false;
	public $activeParams = array();
	public $baseUri;
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
		$this->baseUri = $this->_makeUri();
	}

	public function &__get($param) {
		if(array_key_exists($param, $this->activeParams)) {
			return $this->activeParams[$param];
		}
		if(property_exists($this, $param)) {
			return $this->{$param};
		}
		return $this->dud;
	}

	public function __destruct() {
		if($this->file != basename($this->skeleton->router->getRoute(), EXT)) return;
		// make sure this request method type hasn't ran already
		if(isset($this->skeleton->callStack[$this->skeleton->request->method()])) return;
		// Add to call Array
		$this->skeleton->callStack[$this->skeleton->request->method()] = str_replace($this->skeleton->router->getRoutePatternKeys(), '{var}', $this->baseUri);
		if(!$this->factoryOutputCalled && is_callable($this->callback)) {
			// run the users callback
			call_user_func($this->callback, $this, $this->skeleton, $this->skeleton->request);
		} else {
			// FACTORY OUTPUT
			$args = explode('/', $this->skeleton->router->getUri());
			$method = (isset($args[0])) ? $args[0] : $this->file;
			$id = (isset($args[1])) ? $args[1] : false;
			$queryString = $this->skeleton->router->getQueryString();
			parse_str($queryString, $queryData);
			switch ($this->skeleton->request->method()) {
				case 'GET':
					if($id) {
						// retreive a specific row
						$row = $this->model->getBy($this->model->getPrimaryKey(), $id);
						$this->out(array(
							'status' => 'success',
							'message' => sprintf('%s Retreived', ucwords(str_replace('_', ' ', Inflector_Helper::singular($this->model->getTable())))),
							'data' => $row
						));
					} else {
						$allRows = $this->model->getAll();
						$this->out(array(
							'status' => 'success',
							'message' => sprintf('All %s Retreived', ucwords(str_replace('_', ' ', Inflector_Helper::plural($this->model->getTable())))),
							'data' => $allRows
						));
					}
					break;

				case 'POST':
					if($id) {
						$this->out(array(
							'status' => 'error',
							'message' => 'Nothing to do'
						));
					} else {
						// create row
					}
					break;

				case 'PUT':
					if($id) {
						// update row
					} else {
						// do nothing
					}
					break;

				case 'DELETE':
					if($id) {
						// delete row
					} else {
						// do nothing
					}
					break;
				
				default:
					$this->out(array(
						'status' => 'error',
						'message' => 'Invalid request'
					));
					break;
			}
		}
	}

	private function _getEndpointFile() {
		$trace = debug_backtrace();
		return strtolower(str_replace(array(EXT, SERVICE_PATH . 'endpoints'), '', basename($trace[2]['file'])));
	}

	public function get($uri, $callback = null) {
		$madeUri = $this->_makeUri($uri);
		$this->skeleton->router->addToMap($madeUri, $this->file);
		if($this->skeleton->router->getPreloadFlag() == true) return;
		$this->_makeParams($uri);
		if($this->skeleton->request->method() != 'GET') return;
		// make sure URI is valid
		if(!$this->skeleton->router->routeMatch($madeUri, $this->skeleton->router->getUri())) return;
		// make sure its not a duplicate request
		if(in_array($madeUri, $this->skeleton->callStack)) return;
		// continue!
		$this->skeleton->callStack['GET'] = str_replace($this->skeleton->router->getRoutePatternKeys(), '{var}', $madeUri);
		if(is_array($uri)) {
			return $callback($this);
		}
		if(is_callable($uri)) {
			return $uri($this, $this->skeleton, $this->skeleton->request);
		}
		return $callback($this, $this->skeleton, $this->skeleton->request);
	}

	public function post($uri, $callback = null) {
		$madeUri = $this->_makeUri($uri);
		$this->skeleton->router->addToMap($madeUri, $this->file);
		if($this->skeleton->router->getPreloadFlag() == true) return;
		$this->_makeParams($uri);
		if($this->skeleton->request->method() != 'POST') return;
		// make sure URI is valid
		if(!$this->skeleton->router->routeMatch($madeUri, $this->skeleton->router->getUri())) return;
		// make sure its not a duplicate request
		if(in_array($madeUri, $this->skeleton->callStack)) return;
		// continue!
		$this->skeleton->callStack['POST'] = str_replace($this->skeleton->router->getRoutePatternKeys(), '{var}', $madeUri);
		if(is_array($uri)) {
			return $callback($this);
		}
		if(is_callable($uri)) {
			return $uri($this, $this->skeleton, $this->skeleton->request);
		}
		return $callback($this, $this->skeleton, $this->skeleton->request);
	}

	public function put($uri, $callback = null) {
		$madeUri = $this->_makeUri($uri);
		$this->skeleton->router->addToMap($madeUri, $this->file);
		if($this->skeleton->router->getPreloadFlag() == true) return;
		$this->_makeParams($uri);
		if($this->skeleton->request->method() != 'PUT') return;
		// make sure URI is valid
		if(!$this->skeleton->router->routeMatch($madeUri, $this->skeleton->router->getUri())) return;
		// make sure its not a duplicate request
		if(in_array($madeUri, $this->skeleton->callStack)) return;
		// continue!
		$this->skeleton->callStack['PUT'] = str_replace($this->skeleton->router->getRoutePatternKeys(), '{var}', $madeUri);
		if(is_array($uri)) {
			return $callback($this);
		}
		if(is_callable($uri)) {
			return $uri($this, $this->skeleton, $this->skeleton->request);
		}
		return $callback($this, $this->skeleton, $this->skeleton->request);
	}

	public function delete($uri, $callback = null) {
		$madeUri = $this->_makeUri($uri);
		$this->skeleton->router->addToMap($madeUri, $this->file);
		if($this->skeleton->router->getPreloadFlag() == true) return;
		$this->_makeParams($uri);
		if($this->skeleton->request->method() != 'DELETE') return;
		// make sure URI is valid
		if(!$this->skeleton->router->routeMatch($madeUri, $this->skeleton->router->getUri())) return;
		// make sure its not a duplicate request
		if(in_array($madeUri, $this->skeleton->callStack)) return;
		// continue!
		$this->skeleton->callStack['DELETE'] = str_replace($this->skeleton->router->getRoutePatternKeys(), '{var}', $madeUri);
		if(is_array($uri)) {
			return $callback($this);
		}
		if(is_callable($uri)) {
			return $uri($this, $this->skeleton, $this->skeleton->request);
		}
		return $callback($this, $this->skeleton, $this->skeleton->request);
	}

	public function setModel($tableName = null) {
		$this->model = new Skeleton_Model($tableName);
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
		$this->_makeAndSetUris();
		if($this->skeleton->router->getPreloadFlag() == true) return $this;
		return $this;
	}

	private function _makeAndSetUris() {
		// GET: /all, POST: /all, PUT: /all/:id, DELETE: /all/:id
		$this->skeleton->router->addToMap($this->name, $this->file);
		// GET: /all/:id, PUT: /all/:id, DELETE: /all/:id
		$this->skeleton->router->addToMap($this->name . '/:id', $this->file);
		// ALL ELSE
		if(strpos($this->skeleton->router->getUri(), $this->name) !== false) {
			$this->skeleton->router->addToMap($this->skeleton->router->getUri(), $this->file);
		}
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
			if(strpos($uri, '?') !== false) {
				list($uri, $qString) = explode('?', $uri);
				$uriString .= (strlen($uri)) ? '/' . $uri . '?' . $qString : '?' . $qString;
			} else {
				$uriString .= '/' . $uri;
			}
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