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
}