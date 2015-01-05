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
	public function Endpoint($endpointName, $callback = null) {
		if(is_callable($endpointName)) {
			// endpointName is callback, generate a id for endpoint at time of use
			$callback = $endpointName;
			$trace = debug_backtrace();
			$endpointName = basename($trace[0]['file'], EXT);
			$this->skeleton->router->addToMap($endpointName, $endpointName);
			// $this->skeleton->router->addToMap('', $endpointName);
		}
		if(!is_string($endpointName)) {
			return false;
		}
		return $endpoint = $this->skeleton->{'endpoint_'.$endpointName} = $this->skeleton->endpoints[] = new Endpoint_Entity($endpointName, $callback, $this->skeleton);
	}
}