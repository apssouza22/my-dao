<?php

use helpers as h;
use dao;

$db = new \PDO("mysql:host=localhost;dbname=testedao", "root", "");
$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);




?>
