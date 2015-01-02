<?php
class Skeleton_Router {

	public $skeleton;
	public $request;
	public $output;

	public function __construct($skeleton) {
		$this->skeleton = $skeleton;
		$this->request = json_decode(json_encode($_REQUEST));
	}

	public function getRequest($property = null) {
		return ($property !== null && property_exists($this->request, $property)) ? $this->request->{$property} : $this->request;
	}

	public function map(array $routes) {
		foreach($routes as $route => $action) {

		}
	}

	public function response($method, $uri, $callback) {
		$callback($this->request);
	}
}