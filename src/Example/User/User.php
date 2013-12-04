<?php namespace Example\User;

use Example\Core\AbstractModel;

class User extends AbstractModel {
	public function getDefaults() {
		return [
			'id' => null,
			'name' => '',
			'email' => '',
		];
	}
}