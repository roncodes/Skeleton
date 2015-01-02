<?php
class Skeleton_Load {

	public function __construct() {}

	/**
	 * Loads a library into skeleton
	 * @param  String $libraryName Name of the library to load
	 * @return boolean	true if library loaded
	 */
	public function library(string $libraryName) {
		$file = SERVICE_PATH . 'libraries/' . $libraryName . EXT;
		if(file_exists($file)) {
			list($filePath, $className) = [$file, basename($file, EXT)];
			include $filePath;
            $this->{strtolower(basename($filePath, EXT))} = new $className;
            return true;
		}
		return false;
	}
}