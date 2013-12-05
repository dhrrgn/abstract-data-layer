<?php

require 'vendor/autoload.php';

$connectionFactory = new Aura\Sql\ConnectionFactory;
$connection = $connectionFactory->newInstance('mysql', [
	'host' => 'localhost',
	'port' => '3306',
	'dbname' => 'foo',
], 'foo', 'bar');

$users = new Example\User\UserRepository($connection);
var_dump($users->getById(1));
