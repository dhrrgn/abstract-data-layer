<?php namespace Example\User;

use Example\Core\AbstractRepository;

class UserRepository extends AbstractRepository implements UserRepositoryInterface {
	public function getById($id) {
		return $this->findOne(function ($stmt) {
			$stmt->where('id = :id');
		}, ['id' => $id]);
	}

	protected function getTableName() {
		return 'users';
	}

	protected function getModelClass() {
		return 'Example\\User\\User';
	}
}