<?php 
/**
 * Databse Configuration
 */

// Types can be mysql, pgsql, sqlite, and cubrid
$config->database('sutracampDb:development', array(
	'type' => 'mysql',
	'port' => 3306,
	'host' => 'localhost',
	'dbname' => 'sutracamp',
	'user' => 'root',
	'password' => ''
));