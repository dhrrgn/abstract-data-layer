<?php namespace Example\Core;

abstract class AbstractRepository {

	abstract protected function getTableName();
	abstract protected function getModelClass();

	protected $dbh = null;

	public function __construct($dbh) {
		$this->dbh = $dbh;
	}

	public function findAll(callable $callback = null, array $bindData = []) {
		$stmt = $this->dbh->newSelect();
		$stmt->cols(['*'])
		     ->from($this->getTableName());
		if ($callback) {
			$return = call_user_func($callback, $stmt);
			if ( ! is_null($return)) {
				$stmt = $return;
			}
		}

		$results = $this->dbh->fetchAll($stmt, $bindData);

		if ($results) {
			return $this->createModels($results);
		}
		return null;
	}

	public function findOne(callable $callback = null, array $bindData = []) {
		$stmt = $this->dbh->newSelect();
		$stmt->cols(['*'])
		     ->from($this->getTableName());
		if ($callback) {
			$return = call_user_func($callback, $stmt);
			if ( ! is_null($return)) {
				$stmt = $return;
			}
		}

		$results = $this->dbh->fetchOne($stmt, $bindData);

		if ($results) {
			return $this->createModel($results);
		}
		return null;
	}

	public function make(array $data = []) {
		return $this->createModel($data, true);
	}

	public function save(AbstractModel $model) {
		if ( ! $model->isNew() && ! $model->isModified()) {
			return false;
		}
		$modelData = $model->rawData();

		$stmt = $this->dbh->newInsert();
		$stmt->into($this->getTableName())
			 ->cols(array_keys($modelData));

		return $this->dbh->query($stmt, $modelData);
	}

	protected function createModel(array $data, $isNew = false) {
		$cls = $this->getModelClass();
		return new $cls($data, $isNew);
	}

	protected function createModels(array $results, $isNew = false) {
		$models = [];
		foreach ($results as $row) {
			$models[] = $this->createModel($row, $isNew);
		}

		return $models;
	}
}