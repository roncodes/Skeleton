<?php
/**
 * Skeleton PHP Rest Framework
 * --------------------------------
 * Skeleton is an easy to use REST framework 
 * that builds based on database tables
 */

/**
 * Base paths
 */
$skeleton_path = 'skeleton';
$service_path = 'service';

/**
 * Error Reporting
 */
error_reporting(E_ALL);

/**
 * Get and set skeleton path and set current dir for CLI
 */
if (defined('STDIN')) {
	chdir(dirname(__FILE__));
}
if (realpath($skeleton_path) !== FALSE) {
	$skeleton_path = realpath($skeleton_path).'/';
}
// ensure there's a trailing slash
$skeleton_path = rtrim($skeleton_path, '/').'/';
// Is the skeleton path correct?
if (!is_dir($skeleton_path)) {
	exit('Yo man yo skeleton path does not appear to be set correctly. Please open the following file and correct this: ' . pathinfo(__FILE__, PATHINFO_BASENAME));
}

/**
 * Get and set service path
 */
if (defined('STDIN')) {
	chdir(dirname(__FILE__));
}
if (realpath($service_path) !== FALSE) {
	$service_path = realpath($service_path).'/';
}
// ensure there's a trailing slash
$service_path = rtrim($service_path, '/').'/';
// Is the skeleton path correct?
if (!is_dir($service_path)) {
	exit('Your skeleton path does not appear to be set correctly. Please open the following file and correct this: ' . pathinfo(__FILE__, PATHINFO_BASENAME));
}

/**
 * Set constants
 */
// The name of this framework
define('FRAMEWORK', 'Skeleton');

// Version
define('SKELETON_VERSION', '0.0.1');

// The name of THIS file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// The PHP file extension
// this global constant is deprecated.
define('EXT', '.php');

// Path to the system folder
define('SKELETON_PATH', str_replace("\\", "/", $skeleton_path));

// Path to the service folder
define('SERVICE_PATH', str_replace("\\", "/", $service_path));

// Path to the front controller (this file)
define('FCPATH', str_replace(SELF, '', __FILE__));

// Name of the "skeleton folder"
define('SKELETONDIR', trim(strrchr(trim(SKELETON_PATH, '/'), '/'), '/'));

// When not to do anything
define('donothing', null);

include('skeleton/Skeleton.php');
