<?php
class Skeleton_Request {

	public $skeleton;
	public $request;
	public $server;

	public function __construct($skeleton) {
		$this->skeleton = $skeleton;
	}

	public function id($hash = true) {}

	public function get() {}

	public function post() {}

	public function put() {}

	public function delete() {}

	public function params() {}

	public function cookies() {}

	public function server($property = null) {
		return (isset($property)) ? $_SERVER[$property] : $_SERVER;
	}

	public function headers() {}

	public function files() {}

	public function body() {}

	public function param($key) {}

	public function isSecure() {}

	public function ip() {}

	public function pathname() {}

	public function method($method = null) {}

	public function isMethod($method) {
		return $this->method($method);
	}

	public function query($key, $value = null) {}
}