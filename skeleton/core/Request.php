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

	/**
	 * Get the request component ready
	 * 
	 * @param Skeleton $skeleton 
	 */
	public function __construct($skeleton) {
		$this->skeleton = $skeleton;
		$this->ipAddress = $this->ip();
		// $this->userAgent = get_browser();
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
		parse_str($this->body, $data); 
		if ($index === null AND !empty($data)) {
			return $data;
		}
		return (array_key_exists($index, $data)) ? $data[$index] : array();
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
		if ($index === null AND !empty($_GET)) {
			return $_GET;
		}
		return (array_key_exists($index, $_GET)) ? $_GET[$index] : false;
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
		if ($index === null AND !empty($_POST)) {
			return $_POST;
		}
		return (array_key_exists($index, $_POST)) ? $_POST[$index] : false;
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
		return $this->_putData($index);
	}

	/**
	 * Parse PUT request, and return put data
	 * 
	 * @param  string $index 
	 * @return string
	 */
	private function _putData($index = null) {
		$data = $this->_incoming();
		if(strpos(current($data), 'WebKitFormBoundary') === false) {
			return array_key_exists($index, $data) ? $data[$index] : $data;
		}
		$data = reset($data);
		$data = preg_split('/------WebKitFormBoundary.*nContent-Disposition: form-data; name=/', $data);
		$put_data = array();
		foreach($data as $input) {
			// get key
			preg_match('/"([^"]+)"/', $input, $key);
			// get data
			$input = preg_replace('/------WebKitFormBoundary.*--/', '', $input);
			$put_data[$key[1]] = trim(str_replace($key[0], '', $input));
		}
		if($index == null) {
			return $put_data;
		}
		return array_key_exists($index, $put_data) ? $put_data[$index] : false;
	}

	/**
	 * Returns all request parameters
	 *
	 * @access public
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
	    if (isset($_SERVER['HTTP_CLIENT_IP']))
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if(isset($_SERVER['REMOTE_ADDR']))
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