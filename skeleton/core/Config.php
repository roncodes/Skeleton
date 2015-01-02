<?php
class Skeleton_Config {

	public $configs = array();

	public function __construct() {

	}

	public function set($property, $value) {
		$this->configs[$property] = $value;
	}

	public function get($property) {
		return (isset($this->configs[$property])) ? $this->configs[$property] : null;
	}
}