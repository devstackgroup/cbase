# cbase
[![Build Status](https://travis-ci.org/devstackgroup/cbase.svg?branch=master)](https://travis-ci.org/devstackgroup/cbase)

CRUD library for MySQL with PDO

## How to use it

### Install with composer

```
$ composer create-project devstackgroup/cbase --stability=dev
```

## Examples
###### Insert example
```php
<?php

require 'vendor/autoload.php';

use CBase\Query\Query;

$db = new Query(require_once 'config/bootstrap.php');
$db->setTable('test');

$db->create([
		'field' => 1
	]);
$db->close();
```

###### Read example
Read All with order by id DESC and limit 2 to assoc array
```php
<?php

require 'vendor/autoload.php';

use CBase\Query\Query;

$db = new Query(require_once 'config/bootstrap.php');
$db->setTable('test');

$data = $db->read(['field'])
	   ->orderBy([
	    'id' => 'DESC'
	   ])
	   ->limit(2)
	   ->get([
	    'all' => true, 
	    'fetch' => 'assoc'
	   ]);
			
var_dump($data);
$db->close();
```
Read All to array
```php
<?php

require 'vendor/autoload.php';

use CBase\Query\Query;

$db = new Query(require_once 'config/bootstrap.php');
$db->setTable('test');

$data = $db->read()
	   ->get();
			
foreach ($data as $value) {
	var_dump($value);
}
$db->close();
```

###### Update example
```php
<?php

require 'vendor/autoload.php';

use CBase\Query\Query;

$db = new Query(require_once 'config/bootstrap.php');
$db->setTable('test');

$db->update([
    'field' => 2
   ])
   ->where([
    'id' => 1
   ])
   ->exec();
$db->close();
```

###### Delete example
```php
<?php

require 'vendor/autoload.php';

use CBase\Query\Query;

$db = new Query(require_once 'config/bootstrap.php');
$db->setTable('test');

$db->delete()
   ->where([
    'id' => 1
   ])
   ->exec();
$db->close();
```
