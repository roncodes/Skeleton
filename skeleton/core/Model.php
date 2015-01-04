<?php
class Skeleton_Model {

	private $table;

	public function __construct($table = null) {
		if(!is_string($table)) {
			return;
		}
		$this->table = $table;
	}

	public function _return($data, $noexport = false) {
		if($noexport === true) return $data;
		return R::exportAll($data);
	}

	public function get($id) {
		return $this->_return(R::load($this->table, $id));
	}

	public function getBean($id) {
		return $this->_return(R::load($this->table, $id), true);
	}

	public function getAll() {
		return $this->_return(R::findAll($this->table));
	}

	public function getAllBeans() {
		return $this->_return(R::findAll($this->table), true);
	}

	public function getBatch($ids = array()) {
		if(empty($ids)) return false;
		return $this->_return(R::loadAll($this->table, $ids));
	}

	public function getBatchOfBeans($ids = array()) {
		if(empty($ids)) return false;
		return $this->_return(R::loadAll($this->table, $ids), true);
	}

	public function update($id, $data = array()) {
		if(empty($data)) return false;
		$obj = R::load($this->table, $id);
		foreach ($data as $property => $value) {
			$obj->{$property} = $value;
		}
		return R::store($obj);
	}

	public function insert($data = array()) {
		if(empty($data)) return false;
		$obj = R::dispense($this->table);
		foreach ($data as $property => $value) {
			$obj->{$property} = $value;
		}
		return R::store($obj);
	}

	public function delete($id = null) {
		$obj = R::load($this->table, $id);
		return R::trash($obj);
	}

}