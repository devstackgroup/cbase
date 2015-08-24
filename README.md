# cbase
[![Build Status](https://travis-ci.org/devstackgroup/cbase.svg?branch=master)](https://travis-ci.org/devstackgroup/cbase)
[![Latest Stable Version](https://poser.pugx.org/devstackgroup/cbase/v/stable)](https://packagist.org/packages/devstackgroup/cbase) [![Total Downloads](https://poser.pugx.org/devstackgroup/cbase/downloads)](https://packagist.org/packages/devstackgroup/cbase)  [![License](https://poser.pugx.org/devstackgroup/cbase/license)](https://packagist.org/packages/devstackgroup/cbase)

CRUD library for MySQL with PDO

By [ComStudio](http://comstudio.pl)

## How to use it

### Install with composer

```
$ composer create-project devstackgroup/cbase --stability=dev
```
### Configuration

```php
# config/bootstrap.php

<?php
	return new PDO(
				 'mysql:host=127.0.0.1;dbname=dbname',
				 'dbuser',
				 'dbpassword'
			    );
```
* ```127.0.0.1``` - host address
* ```dbname``` - database name
* ```dbuser``` - database username
* ```dbpassword``` - database password

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
