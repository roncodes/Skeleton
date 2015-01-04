<?php
class Skeleton_Load {

	public $skeleton;

	public function __construct($skeleton) {
		// either skeleton or an endpoint
		$this->skeleton = $skeleton;
	}

	/**
	 * Loads a library into skeleton
	 * @param  String $libraryName Name of the library to load
	 * @return boolean	true if library loaded
	 */
	public function library($libraryName, $alias = null) {
		$file = SERVICE_PATH . 'libraries/' . $libraryName . EXT;
		if(file_exists($file)) {
			list($filePath, $className) = [$file, basename($file, EXT)];
			include $filePath;
			$propertyName = ($alias == null) ? strtolower(basename($filePath, EXT)) : $alias;
            $lib = $this->skeleton->{$propertyName} = new $className;
            $this->skeleton->libraries[] = $lib;
            return true;
		}
		return false;
	}

	public function model($modelName, $alias = null) {
		$file = SERVICE_PATH . 'models/' . $modelName . EXT;
		if(file_exists($file)) {
			list($filePath, $className) = [$file, basename($file, EXT)];
			include $filePath;
			$propertyName = ($alias == null) ? strtolower(basename($filePath, EXT)) : $alias;
            $model = $this->skeleton->{$propertyName} = new $className;
            $this->skeleton->models[] = $model;
            return true;
		}
		return false;
	}
}