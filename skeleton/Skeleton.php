<?php
class Skeleton {

    protected $bones = array(
        'dependencies',
        'helpers',
        'entities'
    );
    public $loadedBones = array();
    public $endpoints = array();
    public $libraries = array();
    public $environment;
    public $coreComponents = array();

    public function __construct() {
        // Autoload Dependencies & Helpers
        spl_autoload_register([$this, 'loadBones']);
        // Load in Core Components
        foreach (glob(SKELETON_PATH . 'core/*.php') as $file) {
            list($filePath, $className) = [$file, 'Skeleton_' . basename($file, EXT)];
            if(file_exists($filePath)) {
                include $filePath;
                $this->{strtolower(basename($filePath, EXT))} = new $className($this);
                $this->coreComponents[] = strtolower(basename($filePath, EXT));
            }
        }
        // Core Components Loaded!
        foreach($this->coreComponents as $component) {
            if(method_exists($this->{$component}, '_onLoadFinish')) {
                $this->{$component}->_onLoadFinish();
            }
        }
        // Run App
        $skeleton = $this;
        include(SERVICE_PATH . 'endpoints/' . $this->router->output . EXT);
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