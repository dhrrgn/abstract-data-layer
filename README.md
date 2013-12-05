# Abstract Data Layer

This is an example of a nice way to abstract your data layer.  The example is using  [Aura\Sql](https://github.com/auraphp/Aura.Sql) (and so should you).

You will notice the Model never touches the database...that is the Repositories' job.

### Example Usage

```php
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
```

