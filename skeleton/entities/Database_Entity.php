<?php
class Database_Entity {

	private $connection = array();

	public function __construct($alias = null, $environment = null, $port = null, $type = null, $host = null, $name = null, $user = null, $password = null) {
		if(is_array($alias)) {
			$this->connection = $alias;
		} else {
			$this->alias = $alias;
			$this->environment = $environment;
			$this->type = $type;
			$this->host = $host;
			$this->port = $port;
			$this->name = $name;
			$this->user = $user;
			$this->password = $password;
		}
	}

	public function __set($key, $value) {
		$this->connection[$key] = $value;
	}

	public function __get($key) {
		if(array_key_exists($key, $this->connection)) {
			return $this->connection[$key];
		}
		return null;
	}

	public function getConnectionAsString() {
		return sprintf('%s:host=%s;port=%s;dbname=%s;user=%s;password=%s', $this->type, $this->host, $this->port, $this->name, $this->user, $this->password);
	}

	public function getConnection() {
		return $this->connection;
	}

}