<?php

require 'vendor/autoload.php';

use CBase\Query\Query;


$db = new Query(require_once 'config/bootstrap.php');
$db->setTable('test');

/**
 * 
 * Put Your query here
 * 
 */
 
$db->close();
