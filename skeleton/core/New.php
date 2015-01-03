<?php
class Skeleton_New {

	public $skeleton;

	public function __construct($skeleton) {
		$this->skeleton = $skeleton;
	}

	/**
	 * Creates a new endpoint entity/object
	 * @param String $endpointName Name of the entity
	 */
	public function Endpoint($endpointName) {
		$endpoint = new Endpoint_Entity($endpointName);
		return $endpoint;
	}

	public function Database($alias = null, $environment = null, $port = null, $type = null, $host = null, $name = null, $user = null, $password = null) {
		$database = new Database_Entity($alias, $environment, $port, $type, $host, $name, $user, $password);
		return $database;
	}
}