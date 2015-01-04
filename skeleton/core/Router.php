<?php
class Skeleton_Router {

	public $skeleton;
	public $request;
	public $route;
	public $map = array();
	public $default_endpoint;

	public function __construct($skeleton) {
		$this->skeleton = $skeleton;
		$this->default_endpoint = 'test';
	}

	public function request($property = null) {
		return ($property !== null && property_exists($this->request, $property)) ? $this->request->{$property} : $this->request;
	}

	public function map(array $routes) {
		foreach($routes as $route => $action) {
			$this->map[$route] = $action;
		}
	}

	public function response($method, $uri, $callback) {
		return $callback($this->request);
	}

	public function _onLoadFinish() {
		$currentUri = $this->skeleton->request->server('REQUEST_URI');
		foreach($this->map as $uri => $endpoint) {
			if($uri == $currentUri) {
				$this->route = $endpoint;
				return;
			}
		}
		$this->route = $this->default_endpoint;
	}
}