<?php
namespace IPHP\Model;
use IPHP\Database\DB;
use IPHP\Database\Selectable;
use IPHP\Database\Updateable;
use IPHP\Database\Insertable;
use IPHP\Database\Delete;
use IPHP\Database\Where;

class Model {
	private static $defaultConnection = null;
	private $connection = null;
	protected $table = '';
	protected $primaryKeys = [];
	protected $ai = true;
	protected $fields;
	protected $onSave = 'insert';
	protected $hasOne = [];
	protected $hasMany = [];
	protected $with = [];

	public static function setDefaultConnection (DB $db) {
		self::$defaultConnection = $db;
	}

	public function __construct (DB $db = null) {
		if (!$db) {
			if (!self::$defaultConnection) {
				throw new \Exception("Connection is manditory as no default connection has been set!");
			} else {
				$this->connection = self::$defaultConnection;
			}
		} else {
			$this->connection = $db;
		}
	}
	/**
	 * Require returns the queriable expression
	 * 
	 * @return 
	 */
	public function select(...$fields) {
		return (new Selectable($this->table))->select($fields);
	}
	/**
	 * Set the queryiable to find with an id set
	 */
	public function find (...$ids) {
		$where = new Where;
		$i = 0;
		foreach ($ids as $id) {
			if (!isset($this->primaryKeys[$i])) {
				return NULL;
			}
			$where->equals($this->primaryKeys[$i], $id);

			$i++;
		}
		
		return $this->getOne((new Selectable($this->table))->where($where));
	}

	public function get (Selectable $select) {
		$queryResult = $this->connection->fetch($select->getComputedQuery(), $select->getValues());
		if ($queryResult === false) {
			throw new \Exception("Query cause error: ". print_r($this->connection->getErrors(), true) . ' print '. $select->getComputedQuery());
		}
		//print($query);
		return $this->fill($queryResult);
	}

	public function getOne (Selectable $select) {
		$queryResult = $this->connection->fetch($select->getComputedQuery(), $select->getValues());
		if ($queryResult === false) {
			throw new \Exception("Query cause error: ". print_r($this->connection->getErrors(), true) . ' print '. $select->getComputedQuery());
		}
		$result = $this->fill($queryResult, true);

		

		return (count($result) >= 1 ? $result[0] : []) ;
	}

	private function fill ($results, $single = false) {
		$collection = [];
		if (count($results) > 0){
			$prototype = new static;
			$prototype->onSave = 'update';

			if ($single || count($results) == 1) {
				$prototype->fields = $results;
				$collection[] = $prototype;
			} else {
				foreach ($results as $index => $result) {
					$model = clone $prototype;
					$model->fields = $result;
					$collection[] = $model;
				}
			}
		}

		if (!empty($this->with)) {
			$this->retriveRelated($collection);
		}

		return $collection;
	}

	public function save () {
		$sql = NULL;
		$fields = (array)$this->fields;
		if ($this->onSave == 'insert') {
			$sql = new Insertable($this->table);
		} else {

			if ($this->ai && count($this->primaryKeys) == 1) {
				if (array_key_exists($this->primaryKeys[0], $fields)) {
					unset($fields[$this->primaryKeys[0]]);
				}
			}
			$sql = new Updateable($this->table);			
		}
		
		$queriable = $sql->fields(array_keys($fields));
		$sql = $queriable->getComputedQuery();
		$values = array_values($fields);


		if (!$this->connection->executeQuery($sql, $values, false)){
			throw new \Exception("Could not save model due to an error: ". $this->connection->getErrors());
		}

		if ($this->onSave == 'insert' && $this->ai) {
			$id = $this->connection->insertId();

			if ($id > 0 && count($this->primaryKeys) == 1) {
				$this->set($this->primaryKeys[0], $id);
			}
		}

		return true;
	}

	public function delete () {
		$delete = new Delete($this->table);

		$where = new Where();

		foreach ($this->primaryKeys as $key) {
			$value = $this->retrive($key);
			if ($value === NULL) {
				throw new \Exception("Value not set for primary key: '". $key . "'");
			}
			$where->equals($key, $this->retrive($key));
		}

		$delete->where($where);

		$sql 		= $delete->getComputedQuery();
		$values 	= $delete->getValues();

		$result =  $this->connection->delete($sql, $values);

		if (!$result) {
			throw new \Exception("Could not delete item");
		}

		return true;
	}

	public function set ($key, $value) {
		$this->fields[$key] = $value;
	}

	public function retrive ($key) {
		return isset($this->fields->{$key}) ? $this->fields->{$key} : NULL;
	}

	public function contents () {
		return (array)$this->fields;
	}

	public function with (...$methods) {
		foreach ($methods as $method) {
			$method = (string)$method;

			if (!method_exists($this, $method)) {
				throw new \Exception("Method '". $method ."' does not exist");
			}
		}

		return $this;
	}

	public function hasOne (Model $model, $foreignKey, $primaryKey) {
		$this->hasOne[] = [
			'model' => $model,
			'foreignKey' => $foreignKey,
			'primaryKey' => $primaryKey
		];
	}

	public function hasMany () {

	}

	private function registerRelated () {
		foreach ($this->with as $method) {
			$this->{$method}();
		}
	}

	private function retriveRelated (array &$collection = []) {
		$this->registerRelated();
	
		$keys = [];
		foreach ($this->hasOne as $related) {
			$keys[$related['foreignKey']] = [];
		}

		foreach ($collection as $index => $model) {
			foreach ($keys as $key => &$values) {
				$value = $model->retrive($key);
				$values[$value][] = $index;
			}
		}

		$

	}

	protected function setOne () {
		//
	}

	public function getRelated ($key) {
		//return related 
	}

}