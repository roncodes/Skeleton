<?php
class Skeleton_Load {

	public $skeleton;

	public function __construct($skeleton) {
		$this->skeleton = $skeleton;
	}

	/**
	 * Loads a library into skeleton
	 * @param  String $libraryName Name of the library to load
	 * @return boolean	true if library loaded
	 */
	public function library($libraryName) {
		$file = SERVICE_PATH . 'libraries/' . $libraryName . EXT;
		if(file_exists($file)) {
			list($filePath, $className) = [$file, basename($file, EXT)];
			include $filePath;
            $this->skeleton->{strtolower(basename($filePath, EXT))} = new $className;
            $this->skeleton->libraries[] = new $className;
            return true;
		}
		return false;
	}
}