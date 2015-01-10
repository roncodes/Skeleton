<?php
class Skeleton_Request {

	/**
	 * Skeleton, itself
	 * 
	 * @var Skeleton
	 */
	private $skeleton;

	/**
	 * IP address of the current user
	 *
	 * @var string
	 */
	public $ipAddress = false;

	/**
	 * user agent (web browser) being used by the current user
	 *
	 * @var string
	 */
	public $userAgent = false;

	/**
	 * List of all HTTP request headers
	 *
	 * @var array
	 */
	protected $headers = array();

	/**
	 * Request parameters
	 *
	 * @var array
	 */
	protected $params = array();

	/**
	 * Request body
	 *
	 * @var string
	 */
	private $body = null;


	public function __construct($skeleton) {
		$this->skeleton = $skeleton;
		$this->ipAddress = $this->ip();
		$this->userAgent = get_browser();
		$this->params = $this->_incoming();
		$this->body = $this->body();
	}

	/**
	* Fetch data from a incoming request input
	*
	* @access	public
	* @param	string
	* @return	string
	*/
	private function _incoming($index = null) {
		parse_str(file_get_contents('php://input'), $data); 
		if ($index === NULL AND !empty($data)) {
			return $data;
		}
		return $data[$index];
	}

	/**
	* Fetch an item from the GET array
	*
	* @access	public
	* @param	string
	* @return	string
	*/
	public function get($index = null) {
		// Check if a field has been provided
		if ($index === NULL AND !empty($_GET)) {
			return $_GET;
		}
		return $_GET[$index];
	}

	/**
	* Fetch an item from the POST array
	*
	* @access	public
	* @param	string
	* @return	string
	*/
	public function post($index = null) {
		// Check if a field has been provided
		if ($index === NULL AND !empty($_POST)) {
			return $_POST;
		}
		return $_POST[$index];
	}

	/**
	* Fetch data from DELETE request
	*
	* @access	public
	* @param	string
	* @return	string
	*/
	public function delete($index = null) {
		return $this->_incoming($index);
	}

	/**
	* Fetch data from PUT request
	*
	* @access	public
	* @param	string
	* @return	string
	*/
	public function put($index = null) {
		return $this->_incoming($index);
	}

	/**
	 * Returns all request parameters
	 * 
	 * @return array
	 */
	public function params() {
		return $this->params;
	}

	public function cookies() {}

	public function server($property = null) {
		if($property === null) { 
			return $_SERVER;
		}
		return (isset($_SERVER[$property])) ? $_SERVER[$property] : false;
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

	/**
	 * get or set parameter to request parameters
	 * 
	 * @param  string $key   key or property
	 * @param  ? $value value they want to set for key
	 * @return value of parameter
	 */
	public function param($key, $value = null) {
		if(!$key) return false;
		if($value !== null) {
			$this->params[$key] = $value;
		}
		return (array_key_exists($key, $this->params)) ? $this->params[$key] : false;
	}

	/**
	 * Returns the clients ip address
	 * 
	 * @return string
	 */
	public function ip() {
	    if ($_SERVER['HTTP_CLIENT_IP'])
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if($_SERVER['HTTP_X_FORWARDED'])
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if($_SERVER['HTTP_FORWARDED_FOR'])
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if($_SERVER['HTTP_FORWARDED'])
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if($_SERVER['REMOTE_ADDR'])
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}

	/**
	 * the request method being used
	 * 
	 * @param  string $is method
	 * @return string  method in question 
	 */
	public function method($is = null) {
        $method = $this->server('REQUEST_METHOD');
        if (null !== $is) {
            return strcasecmp($method, $is) === 0;
        }
        return $method;
    }

    /**
	 * alias for method()
	 * 
	 * @param  string $is method
	 * @return boolean true if method is infact being used
	 */
	public function isMethod($method) {
		return $this->method($method);
	}
}