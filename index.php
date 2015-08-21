<?php

require 'vendor/autoload.php';

use CBase\Query\Query;

$pdo = new PDO("mysql:host=127.0.0.1;dbname=dbname",'dbuser','dbpassword');

$db = new Query($pdo);
$db->setTable('test');

// Example 1: Read All with order by id DESC and limit 2 to assoc array
$data = $db->read(['pole'])
			->orderBy([
				'order' => ['id' => 'DESC']
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
