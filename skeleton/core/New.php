<?php
class Skeleton_New {

	public function __construct() {}

	/**
	 * Creates a new endpoint entity/object
	 * @param String $endpointName Name of the entity
	 */
	public function Endpoint(string $endpointName) {
		$endpoint = new Endpoint_Entity($endpointName);
		return $endpoint;
	}
}