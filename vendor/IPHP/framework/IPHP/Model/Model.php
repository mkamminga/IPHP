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
	protected $updatedFields = [];
	protected $onSave = 'insert';
	protected $related = [];
	protected $softDelete = false;

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

		$this->fields = new \stdClass;
	}
	/**
	 * Require returns the queriable expression
	 * 
	 * @return 
	 */
	public function select(...$fields) {
		$select = (new Selectable($this->table))->select($fields);
		
		if ($this->softDelete) {
			$select->where((new Where)->isNull('`'. $this->table .'`.`deleted_at`'));
		}

		return $select;
	}
	/**
	 * Set the queryiable to find with an id set
	 */
	public function find (...$ids) {
		return $this->findByIds($ids);
	}
	/**
	 * Find a resource by id(s) of fail (throws an exception)
	 * 
	 * @return Model
	 */
	public function findOrFail (...$ids) {
		$model = $this->findByIds($ids);
		
		if (!$model) {
			throw new \Exception("Failed to retreive resource!");
		}
		
		return $model;
	}
	
	private function findByIds (array $ids = []) {
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

	private function farceGet (Selectable $select, $many = true) {
		if ($many) {
			$queryResult = $this->connection->fetchAll($select->getComputedQuery(), $select->getValues());	
		} else {
			$queryResult = $this->connection->fetch($select->getComputedQuery(), $select->getValues());
		}
		
		if ($queryResult === false && !empty($this->connection->getErrors())) {
			throw new \Exception("Query cause error: ". print_r($this->connection->getErrors(), true) . ' print '. $select->getComputedQuery());
		}

		return $queryResult;
	}

	public function get (Selectable $select) {
		$queryResult = $this->farceGet($select);

		if ($queryResult){
			return $this->fill($queryResult);
		} else {
			return NULL;
		}
	}

	public function getOne (Selectable $select) {
		$queryResult = $this->farceGet($select, false);
		
		if ($queryResult){
			$result = $this->fill($queryResult, true);
			return (count($result) >= 1 ? $result[0] : []) ;
		} else {
			return NULL;
		}
	}
	
	public function getCollection (Selectable $select) {
		$queryResult = $this->farceGet($select);

		if ($queryResult){
			return $queryResult;
		}
		
		return [];
	}

	private function fill ($results, $single = false) {
		$collection = [];
		if (count($results) > 0){
			$prototype = new static;
			$prototype->onSave = 'update';

			if ($single) {
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

	private function retriveRelated (array $collection = []) {
		foreach ($this->with as $related) {
			$related->injectIntoCollection($collection);
		}
	}
	/**
	 * Returns the clause needed to update or delete
	 * @return [type] [description]
	 */
	private function getUpdateableClause () {
		$where = new Where;
		foreach ($this->primaryKeys as $key) {
			$value = $this->retreive($key);
			if ($value){
				$where->equals($key, $this->retreive($key));
			} else {
				throw new \Exception("Primary key (". $key .") has no value!");
			}
		}

		return $where;
	}

	public function save () {
		$sql = NULL;
		$executeabelSql = '';
		$fields = (array)$this->fields;
		$values = [];

		if ($this->onSave == 'insert') {
			$sql 			= new Insertable($this->table);
			$queriable 		= $sql->fields($fields);
			$executeabelSql = $queriable->getComputedQuery();
			$values 		= $queriable->getValues();
		} else {
			//Nothing to update so lets save the trouble
			if (empty($this->updatedFields)) {
				return true;
			}

			$sql 			= new Updateable($this->table);
			$keyValue 		= [];

			foreach ($this->updatedFields as $key) {
				$keyValue[$key] = $this->retreive($key);
			}

			$sql->fields($keyValue);

			if (!empty($this->primaryKeys)) {
				

				$sql->where($this->getUpdateableClause());
				$executeabelSql 	= $sql->getComputedQuery();
				$values 			= $sql->getValues();
			} else {
				throw new \Exception("No primary keys set!");
			}				
		}

		if (!$this->connection->executeQuery($executeabelSql, $values, false)){
			throw new \Exception("Could not save model due to an error: ". print_r($this->connection->getErrors(), true) . ' '. $executeabelSql);
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
		$delete->where($this->getUpdateableClause());

		$sql 		= $delete->getComputedQuery();
		$values 	= $delete->getValues();

		$result =  $this->connection->delete($sql, $values);

		if (!$result) {
			throw new \Exception("Could not delete item");
		}

		return true;
	}

	public function softDelete () {
		$this->set('deleted_at', date('Y-m-d h:i:s'));

		return $this->save();
	}

	public function set ($key, $value) {
		if ($this->retreive($key) != $value) {
			$this->fields->{$key} = $value;

			if ($this->onSave == 'update' && !in_array($key, $this->updatedFields)) {
				$this->updatedFields[] = $key;
			}
		}
	}

	public function retreive ($key) {
		return isset($this->fields->{$key}) ? $this->fields->{$key} : NULL;
	}

	public function contents () {
		$data = $this->fields;

		if (!empty($this->related)) {
			foreach ($this->related as $key => $model) {
				if (!isset($data->{$key})) {
					$data->{$key} = $model->contents();
				} else {
					$newKey = 'related_' . $key; 
					$data->{$newKey} = $model->contents();
				}
			}
		}

		return $data;
	}

	public function with (...$methods) {
		foreach ($methods as $method) {
			$method = (string)$method;

			if (!method_exists($this, $method)) {
				throw new \Exception("Method '". $method ."' does not exist");
			} else {
				$this->with[] = $this->{$method}();
			}
		}

		return $this;
	}
	
	public function withRelated (ModelRelation $related) {
		$this->with[] = $related;
		
		return $this;
	}

	public function setRelated ($name, Model $modelRelated) {
		$this->related[$name] = $modelRelated;
	}

	public function setRelatedCollection ($name, array $collection = []) {
		$this->related[$name] = $collection;
	}

	public function getRelated ($key) {
		return (array_key_exists($key, $this->related) ? $this->related[$key] : NULL);
	}

}