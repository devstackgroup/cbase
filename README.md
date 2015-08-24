# cbase
[![Build Status](https://travis-ci.org/devstackgroup/cbase.svg?branch=master)](https://travis-ci.org/devstackgroup/cbase)

CRUD library for MySQL with PDO

## How to use it

### Install with composer

```
$ composer create-project devstackgroup/cbase --stability=dev
```

## Examples
###### Insert exemple
```php
<?php

require 'vendor/autoload.php';

use CBase\Query\Query;

$db = new Query(require_once 'config/bootstrap.php');
$db->setTable('test');

$db->create([
		'field' => 1
	]);
```
