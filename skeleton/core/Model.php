<?php
class Skeleton_Model {

	protected $table;
	protected $primaryKey = 'id';

	public function __construct($table = null) {
		if($table !== null) {
			$this->_setTable($table);
		} else {
			$this->_fetchTable();
		}
	}

	public function _setTable($table = null) {
        $this->table = ($table !== null) ? $table : $this->_fetchTable();
    }

    public function _setPrimaryKey($key = null) {
    	$this->primaryKey = $key;
    }

    private function _fetchTable() {
        if ($this->table == NULL) {
            $this->table = Inflector_Helper::plural(preg_replace('/(_m|_model)?$/', '', strtolower(get_class($this))));
        }
    }

	private function _return($data, $noexport = false) {
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