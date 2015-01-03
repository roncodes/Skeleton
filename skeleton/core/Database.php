<?php
class Skeleton_Database {

	private $connections;

	public function __construct($skeleton) {
		$this->skeleton = $skeleton;
	}

	public function add(Database_Entity $database) {
		$this->connections[$database->alias] = $database;
		R::addDatabase($database->alias, sprintf('%s:host=%s;dbname=%s', $database->type, $database->host, $database->name), $database->user, $database->password);
		$this->select($database->alias);
		return true;
	}

	public function select($dbAlias) {
		if(array_key_exists($dbAlias, $this->connections)) {
			R::selectDatabase($dbAlias);
			return true;
		}
		return false;
	}
}