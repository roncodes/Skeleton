<?php
class Skeleton {

    /**
     * Bones are dependencies that will be autoloaded 
     * 
     * @var array
     */
    protected $bones = array(
        'dependencies',
        'helpers',
        'entities',
        'formats',
        'exceptions'
    );

    /**
     * bones that have been loaded
     * 
     * @var array
     */
    private $loadedBones = array();

    /**
     * active endpoints
     * 
     * @var array
     */
    public $endpoints = array();

    /**
     * libraries loaded into skeleton
     * 
     * @var array
     */
    private $libraries = array();

    /**
     * models loaded into skeleton
     * 
     * @var array
     */
    private $models = array();

    /**
     * current environment
     * 
     * @var string
     */
    private $environment;

    /**
     * core components loaded into skeleton
     * 
     * @var array
     */
    private $coreComponents = array();

    /**
     * request methods that have been called
     * 
     * @var array
     */
    public $callStack = array();

    /**
     * if a skeleton had a heart, this would be it
     */
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
        // Set environment
        $this->environment = ENVIRONMENT;
        if(!$this->environment) {
            exit(JSON::out('error', 'No environment set!'));
        }
        // Preload stuff is over
        $this->router->setPreloadFlag(false);
        // Run App
        try {
            $skeleton = $this;
            include $this->router->go();
            // the end
            die();
        } catch(NoRouteFoundException $e) {
            JSON::out(array(
                'status' => 'error', 
                'message' => $e->getMessage()
            ));
        } catch(Exception $e) {
            JSON::out(array(
                'status' => 'error', 
                'message' => $e->getMessage()
            ));
        }
    }

    /**
     * allows you to add a directory of bones to be loaded
     * 
     * @param string $boneDir
     */
    public function addBones($boneDir) {
        $this->bones[] = $boneDir;
    }

    /**
     * loads all bones using php autoloader
     * 
     * @param  string $className
     * @return void
     */
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

/**
 * Start instance
 * 
 * @var Skeleton
 */
$skeleton = new Skeleton();