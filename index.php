<?php

require 'vendor/autoload.php';

use CBase\Query\Query;

$pdo = new PDO("mysql:host=127.0.0.1;dbname=dbname",'dbuser','dbpassword');

$db = new Query($pdo);
$db->setTable('test');

/**
*
* INSERT into Table
*
*/

// Example: Create field and set value
$db->create([
		'field' => 1
	]);

/**
*
* READ from Table
*
*/

// Example 1: Read All with order by id DESC and limit 2 to assoc array
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

// Example 2: Read All to array
$data =  $db->read()
			->get();

foreach ($data as $value) {
	var_dump($value);
}

/**
*
* UPDATE a Table
*
*/

// Example: Update field and set new value where id = 1
$db->update([
		'field' => 2
	])
	->where([
			'id' => 1
		])
	->exec();

/**
*
* DELETE from Table
*
*/

// Example: Delete field from table where id = 1
 $db->delete()
	->where([
			'id' => 1
		])
	->exec();

/**
*
* Close connection
*
*/
$db->close();