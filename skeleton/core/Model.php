<?php
class Skeleton_Model {

	/**
	 * the table for the model to work on
	 * 
	 * @var string
	 */
	protected $table;

	/**
	 * the primary key for the table
	 * 
	 * @var string
	 */
	protected $primaryKey = 'id';

	/**
	 * columns required for insert
	 * 
	 * @var array
	 */
	private $requiredColumns = array();

	/**
	 * use soft delete
	 *
	 * @var boolean
	 */
	private $softDelete = false;

	/**
	 * the column to soft delete with
	 */
	private $softDeleteColumn = 'deleted';

	/**
	 * Set table and generate model
	 * 
	 * @param [type] $table [description]
	 */
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

	/**
	 * sets required columns for insert
	 * 
	 * @param array $columns
	 */
	public function setRequiredColumns(array $columns) {
		return $this->requiredColumns = $columns;
	}

	public function addRequiredColumn($columnName) {
		return $this->requiredColumns[] = $columnName;
	}

	public function softDelete(boolean $activate, $column = null) {
		$this->softDelete = $active;
		if($column !== null && is_string($column)) {
			$this->softDeleteColumn = $column;
		}
		return $activate;
	}

	public function setSoftDeleteColumn($column) {
		if(is_string($column)) {
			return $this->column = $column;
		}
		return false;
	}

	public function getPrimaryKey() {
		return $this->primaryKey;
	}

	public function getTable() {
		return $this->table;
	}

	public function get($id) {
		return $this->getBy($this->primaryKey, $id);
	}

	public function getBy($column, $value) {
		return $this->_return(R::findOne($this->table, sprintf('%s = ?', $column), [$value]));
	}

	public function getBean($id) {
		return $this->_return(R::findOne($this->table, sprintf('%s = ?', $column), [$value]), true);
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
		// $obj = R::load($this->table, $id);
		$obj = R::findOne($this->table, sprintf('%s = ?', $this->primaryKey), [$id]);
		foreach ($data as $property => $value) {
			$obj->{$property} = $value;
		}
		return R::store($obj);
	}

	public function insert($data = array(), $verify = false) {
		$validated = true;
		if($verify) {
			$validated = $this->verifyColumns($data);
			if(!empty($this->requiredColumns)) {
				foreach($this->requiredColumns as $col) {
					//@todo throw required column missing exception
					if(!in_array($col, $data)) return false;
				}
			}
		}
		// no data to insert @todo throw exception
		if(empty($data)) return false;
		if($validated) {
			$obj = R::dispense($this->table);
			foreach ($data as $property => $value) {
				$obj->{$property} = $value;
			}
			return R::store($obj);
		}
		// data failed validation
		return false;
	}

	public function verifyColumns(array $data) {
		$fields = R::inspect($this->table);
		foreach(array_keys($data) as $property) {
			if(!in_array($property, $fields)) return false;
		}
		return true;
	}

	public function delete($id = null) {
		if($this->softDelete) {
			return $this->update($id, array($this->softDeleteColumn => 1));
		}
		$obj = R::findOne($this->table, sprintf('%s = ?', $this->primaryKey), [$id]);
		if($obj !== null) {
			R::trash($obj);
			return true;
		} 
		return false;
	}

}