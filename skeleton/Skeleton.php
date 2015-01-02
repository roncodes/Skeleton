<?php
class Skeleton {

    protected $bones = array(
        'dependencies'
    );

    public $loadedBones = array();

    public function __construct() {
        // Autoload Dependencies & Helpers
        spl_autoload_register([$this, 'loadBones']);
        // Core Properties
        foreach (glob(SKELETON_PATH . 'core/*.php') as $file) {
            list($filePath, $className) = [$file, 'Skeleton_' . basename($file, EXT)];
            if(file_exists($filePath)) {
                include $filePath;
                $this->{strtolower(basename($filePath, EXT))} = new $className;
            }
        }
        // Finish Up
        $this->loadConfigs();
    }

    public function addBones($boneDir) {
        $this->bones[] = $boneDir;
    }

    private function loadBones($className) {
        // load dem bones
        $requiredBone = $className . EXT;
        foreach($this->bones as $bone) {
            $bonePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, join([SKELETON_PATH, $bone, DIRECTORY_SEPARATOR, $requiredBone]));
            if(file_exists($bonePath)) {
                require $bonePath;
                break;
            }
        }
    }

    private function loadConfigs() {
        $configDir = SERVICE_PATH . 'config/';
        $config = $this->config;
        foreach (glob($configDir . '*.php') as $configFile) {
            if(file_exists($configFile)) {
                include $configFile;
            }
        }
    }
}

$skeleton = new Skeleton();