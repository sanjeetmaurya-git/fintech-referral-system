<?php
require 'vendor/autoload.php';
// We need to define some constants to load CI4
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require 'app/Config/Paths.php';
$paths = new \Config\Paths();
require 'vendor/codeigniter4/framework/system/Test/bootstrap.php';

$db = \Config\Database::connect();
$db->simpleQuery('DROP TABLE IF EXISTS jobs');
$db->simpleQuery('DROP TABLE IF EXISTS worker_documents');
$db->simpleQuery('DROP TABLE IF EXISTS workers');
$db->simpleQuery('DROP TABLE IF EXISTS work_subcategories');
$db->simpleQuery('DROP TABLE IF EXISTS work_categories');
echo 'Cleaned';
