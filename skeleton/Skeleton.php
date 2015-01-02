<?php
class Skeleton {

    protected $bones = array(
        'dependencies'
    );
    public $loadedBones = array();
    public $endpoints = array();
    public $libraries = array();

    public function __construct() {
        // Autoload Dependencies & Helpers
        spl_autoload_register([$this, 'loadBones']);
        // Core Properties
        foreach (glob(SKELETON_PATH . 'core/*.php') as $file) {
            list($filePath, $className) = [$file, 'Skeleton_' . basename($file, EXT)];
            if(file_exists($filePath)) {
                include $filePath;
                $this->{strtolower(basename($filePath, EXT))} = new $className($this);
            }
        }
        // Run App
    }

    public function addBones($boneDir) {
        $this->bones[] = $boneDir;
    }

    private function loadBones($className) {
        // Load dem bones
        $requiredBone = $className . EXT;
        foreach($this->bones as $bone) {
            $bonePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, join([SKELETON_PATH, $bone, DIRECTORY_SEPARATOR, $requiredBone]));
            if(file_exists($bonePath)) {
                require $bonePath;
                $this->loadedBones[] = basename($bonePath, EXT);
                break;
            }
        }
    }
}

$skeleton = new Skeleton();