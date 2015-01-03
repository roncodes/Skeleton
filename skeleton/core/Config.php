<?php
class Skeleton_Config {

	public $configs = array();
	public $skeleton;
	public $configDir;

	public function __construct($skeleton) {
		$this->skeleton = $skeleton;
        $this->configDir = SERVICE_PATH . 'config/';
    }

    public function _onLoadFinish() {
    	$config = $this;
        foreach (glob($this->configDir . '*.php') as $configFile) {
            if(file_exists($configFile)) {
                include $configFile;
            }
        }
    }

	public function set($property, $value) {
		$this->configs[$property] = $value;
	}

	public function get($property) {
		return (isset($this->configs[$property])) ? $this->configs[$property] : null;
	}

	public function database($dbDetails, $connection) {
		// See if dbDetails specify an environment, if it does get it
		if(strpos($dbDetails, ':') !== false) {
			list($dbAlias, $dbEnvironment) = explode(':', $dbDetails);
		} else {
			$dbAlias = $dbDetails;
			$dbEnvironment = $this->skeleton->environment;
		}
		// See if we have a database connection string or array
		if(is_array($connection)) {
			extract($connection);
		} else {
			// parse connection string
			list($type, $connectionString) = explode(':', $connection);
			$parsedConnection = array();
			foreach(explode(';', $connectionString) as $part) {
				list($key, $value) = explode('=', $part);
				$parsedConnection[$key] = $value;
			}
			extract($parsedConnection);
		}
		// Add database entity to Skeleton
		$this->skeleton->database->add(new Database_Entity($dbAlias, $dbEnvironment, $port, $type, $host, $dbname, $user, $password));
	}

	public function route(array $routeMap) {
		$this->skeleton->router->map($routeMap);
	}
}