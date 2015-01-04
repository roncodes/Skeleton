<?php
class Skeleton_Router {

	public $skeleton;
	public $request;
	private $endpointPath;
	protected $route;
	protected $path;
	protected $method;
	protected $map = array();
	protected $defaultEndpoint;

	public function __construct($skeleton) {
		$this->skeleton = $skeleton;
		$this->request = $skeleton->request;
		$this->endpointPath = SERVICE_PATH . 'endpoints/';
		$this->defaultEndpoint = 'test';
	}

	public function go() {
		$route = $this->getFullRoute();
		if(!file_exists($route)) {
			throw new NoRouteFoundException('No route found', 1);	
		}
		$skeleton = $this->skeleton;
		include $route;
	}

	public function getRoute() {
		return $this->route;
	}

	public function getFullRoute() {
		return $this->endpointPath . $this->getRoute() . EXT;
	}

	public function map(array $routes) {
		foreach($routes as $route => $action) {
			$this->map[$route] = $action;
		}
	}

	public function addToMap($route, $action) {
		return $this->map[$route] = $action;
	}

	public function response($method, $uri, $callback) {
		return $callback($this->request);
	}

	public function getUri() {
		return str_replace('endpoint=', '', $this->request->server('QUERY_STRING'));
	}

	public function getUriSegments($index = null) {
		$segments = explode('/', $this->getUri());
		if($index !== null && isset($segments[$index])) {
			return $segments[$index];
		}
		return $segments;
	}

	public function _onLoadFinish() {
		$currentUri = $this->getUri();
		foreach($this->map as $uri => $endpoint) {
			if($uri == 'default') {
				// set default endpoint
				$this->defaultEndpoint = $endpoint;
			}
			if($uri == $currentUri) {
				$this->route = $endpoint;
				return;
			}
		}
		if($currentUri == '/' || $currentUri == '' || $currentUri == $this->defaultEndpoint) {
			$this->route = $this->defaultEndpoint;
		}
	}
}