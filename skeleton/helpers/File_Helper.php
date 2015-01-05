<?php
class File_Helper {

	public static function searchDirectory($needle, $haystack) {
		if(!is_dir($haystack)) return false;
		foreach (glob($haystack . DIRECTORY_SEPARATOR . '*') as $object) {
			if($needle == basename($object, EXT)) {
				// found needle retun filepath and name
				return array(basename($object, EXT) => self::fixPath($object));
			}
		}
		return false;
	}

	public static function fixPath($path) {
		if(!is_string($path)) return false;
		return str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
	}

}