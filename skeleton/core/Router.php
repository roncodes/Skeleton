<?php
class Skeleton_Router {

	public $skeleton;
	public $request;
	private $endpointPath;
	private $preloadFlag = false;
	protected $route = null;
	protected $path;
	protected $method;
	protected $map = array();
	protected $defaultEndpoint = null;
	protected $routePatterns = array(
		':id' => '/([0-9]+)/',
		':int' => '/([0-9]+)/',
		':uuid' => '/[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}/',
		':any' => '/.*/'
	);

	public function __construct($skeleton) {
		$this->skeleton = $skeleton;
		$this->request = $skeleton->request;
		$this->endpointPath = SERVICE_PATH . 'endpoints/';
	}

	public function go() {
		$route = $this->getRoute();
		if(!file_exists($route)) {
			throw new NoRouteFoundException('Method does not exist', 1);	
		}
		return $route;
	}

	public function getRoute() {
		return $this->route;
	}

	public function map(array $routes = array()) {
		if(empty($routes)) {
			return $this->map;
		}
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
		return str_replace($this->getAppBase(), '', $this->request->server('REQUEST_URI'));
	}

	public function getQueryString() {
		return  $this->request->server('QUERY_STRING');
	}

	public function getAppBase() {
		$scriptName = $this->request->server('SCRIPT_NAME');
		return str_replace('index.php', '', $scriptName);
	}

	public function getUriSegments($index = null) {
		$segments = explode('/', $this->getUri());
		if($index !== null && isset($segments[$index])) {
			return $segments[$index];
		}
		return $segments;
	}

	public function getPreloadFlag() {
		return $this->preloadFlag;
	}

	public function setPreloadFlag($flag) {
		return $this->preloadFlag = (boolean) $flag;
	}

	private function _preloadRun($directory = null) {
        $this->preloadFlag = true;
        foreach (glob(($directory === null) ? SERVICE_PATH . 'endpoints/*' : $directory . '/*') as $obj) {
            if(is_dir($obj)) {
                $this->_preloadRun($obj);
                continue;
            } elseif (is_file($obj) && strpos(strtolower(basename($obj)), '.php') !== false) {
                // its a php file
                $this->_gatherRoutesFromEndpoint($obj);
                continue;
            }
        }
        return true;
    }

	private function _gatherRoutesFromEndpoint($file) {
		$skeleton = $this->skeleton;
		include $file;
	}

	private function _getRoutePattern($index) {
		if(array_key_exists($index, $this->routePatterns)) {
			return $this->routePatterns[$index];
		}
		return false;
	}

	public function getRoutePatterns() {
		return $this->routePatterns;
	}

	public function getRoutePatternKeys() {
		return array_keys($this->routePatterns);
	}

	public function _onLoadFinish() {
		$currentUri = $this->getUri();
		$this->_preloadRun();
		foreach($this->map as $uri => $endpoint) {
			if(($uri == 'default' && $currentUri == '') || ($uri == '/' && $currentUri == '') || ($uri == '' && $currentUri == '')) {
				$this->route = $this->_getRouteEndpointFile($endpoint);
				return;
			}
			if(($uri == 'default') || ($uri == '/') || ($uri == '')) {
				$this->defaultEndpoint = $endpoint;
				continue;
			}
			if($this->routeMatch($uri, $currentUri)) {
				$this->route = $this->_getRouteEndpointFile($endpoint);
				return;
			}
		}
		if($currentUri == '/' || $currentUri == '' || $currentUri == $this->defaultEndpoint) {
			$this->route = $this->defaultEndpoint;
		}
	}

	public function routeMatch($routePattern, $realUri) {
		// for example: patten: users/:id -> users/1
		$routePatternSegments = explode('/', $routePattern);
		$realUriSegments = explode('/', $realUri);
		$checkedPatterns = array();
		$index = 0;
		if(count($realUriSegments) != count($routePatternSegments)) return false;
		foreach($realUriSegments as $segment) {
			// see if route pattern segment is regex based
			if(!empty($routePatternSegments[$index]) && is_string($routePatternSegments[$index]) && strpos($routePatternSegments[$index], ':') !== false) {
				$foundRouterPatterns = $this->_extractRoutePatterns($routePatternSegments[$index]);
				$foundRouterPatterns = array_slice($foundRouterPatterns, count($checkedPatterns));
				foreach ($foundRouterPatterns as $pattern) {
					// see if pattern is legal
					preg_match($this->_getRoutePattern($pattern), $segment, $matches);
					if(!count($matches)) return false;
					$checkedPatterns[] = $pattern;
				}
			} else {
				if(isset($routePatternSegments[$index]) && $segment !== $routePatternSegments[$index]) return false;
			}
			$index++;
		}
		return true;
	}

	private function _extractRoutePatterns($pattern) {
		$patternsFound = array();
		$matches = explode(':', $pattern);
		foreach($matches as $match) {
			foreach($this->getRoutePatternKeys() as $pattern) {
				if(strpos($match, str_replace(':', '', $pattern)) !== false) $patternsFound[] = $pattern;
			}
		}
		return $patternsFound;
	}

	private function _getRouteEndpointFile($endpoint = null) {
		$endpointFile = null;
		$uriSegments = $this->getUriSegments();
		if(count($uriSegments) > 1 || $endpoint !== null) {
			$uriSegments = ($endpoint == null) ? $uriSegments : explode('/', $endpoint);
			// find file
			$endpointsDir = new RecursiveDirectoryIterator(SERVICE_PATH . 'endpoints');
			foreach($uriSegments as $segment) {
				foreach (new RecursiveIteratorIterator($endpointsDir) as $file) {
					if (is_file($file) && strpos(basename($file), EXT) !== false && basename($file, EXT) == $segment) {
						$endpointFile = $file;
					}
				}
			}
		} else {
			$endpointFile = $uriSegments[0];
		}
		return $endpointFile;
	}
}