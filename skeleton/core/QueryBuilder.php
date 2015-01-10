<?php
/**
 * Skeleton QueryBuilder works like active record to
 * easily build queries for the DB
 *
 * v0.0.1 -- only supports mysql
 */
class Skeleton_QueryBuilder {

	/**
	 * Skeleton itself
	 * 
	 * @var Skeleton
	 */
	private $skeleton;

	public function __construct($skeleton) {
		$this->skeleton = $skeleton;
	}

	public function select($columns) {
		// select sql columns
	}

	public function where($column, $value = null) {
		// where
	}

	public function join($primaryColumn, $joinColumn, $joinBy, $joinDirection = null) {
		// do a join
	}

}