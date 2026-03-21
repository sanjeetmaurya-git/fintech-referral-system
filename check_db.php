<?php
require 'vendor/autoload.php';
$db = \Config\Database::connect();
print_r($db->listTables());
