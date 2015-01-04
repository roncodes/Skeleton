<?php
class Skeleton_Request {

	public $skeleton;
	public $request;
	public $server;

	public function __construct($skeleton, $body = null) {
		$this->skeleton = $skeleton;
		$this->body = $body ? (string) $body : null;
	}

	public function id($hash = true) {}

	public function get() {}

	public function post() {}

	public function put() {}

	public function delete() {}

	public function params() {}

	public function cookies() {}

	public function server($property = null) {
		return ($property === null) ? $_SERVER : (isset($_SERVER[$property])) ? $_SERVER[$property] : false;
	}

	public function headers() {}

	public function files() {}

	public function body() {
		// Only get it once
        if (null === $this->body) {
            $this->body = @file_get_contents('php://input');
        }
        return $this->body;
	}

	public function param($key, $value = null) {}

	public function isSecure() {}

	public function ip() {}

	public function pathname() {}

	public function method($is = null, $allow_override = true) {
        $method = $this->server('REQUEST_METHOD');
        // Override
        // if ($allow_override && $method === 'POST') {
        //     // For legacy servers, override the HTTP method with the X-HTTP-Method-Override header or _method parameter
        //     if ($this->server('X_HTTP_METHOD_OVERRIDE')) {
        //         $method = $this->server('X_HTTP_METHOD_OVERRIDE');
        //     } else {
        //         $method = $this->param('_method', $method);
        //     }
        //     $method = strtoupper($method);
        // }
        // We're doing a check
        if (null !== $is) {
            return strcasecmp($method, $is) === 0;
        }
        return $method;
    }

	public function isMethod($method) {
		return $this->method($method);
	}

	public function query($key, $value = null) {}

	public function segment($index = null) {
		
	}
}