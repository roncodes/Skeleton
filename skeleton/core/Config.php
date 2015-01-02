<?php
class Skeleton_Config {

	public $configs = array();
	public $skeleton;
	public $configDir;

	public function __construct($skeleton) {
		$this->skeleton = $skeleton;
        $this->configDir = SERVICE_PATH . 'config/';
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
}