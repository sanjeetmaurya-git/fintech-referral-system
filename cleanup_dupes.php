<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(__DIR__);

// Bootstrap CI4
require_once __DIR__ . '/vendor/autoload.php';
$pathsConfig = new \Config\Paths();
// Use the CI4 database config directly
$dbConfig = new \Config\Database();
$default = $dbConfig->default;

$mysqli = new mysqli($default['hostname'], $default['username'], $default['password'], $default['database'], $default['port']);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Remove duplicate categories (keep only IDs 1-6)
$mysqli->query("DELETE FROM work_subcategories WHERE category_id > 6");
$res = $mysqli->query("SELECT ROW_COUNT() as deleted");
echo "Deleted subcategory dupes: " . $res->fetch_assoc()['deleted'] . "\n";

$mysqli->query("DELETE FROM work_categories WHERE id > 6");
$res = $mysqli->query("SELECT ROW_COUNT() as deleted");
echo "Deleted category dupes: " . $res->fetch_assoc()['deleted'] . "\n";

// Count what's left 
$res = $mysqli->query("SELECT COUNT(*) as cnt FROM work_categories");
echo "Remaining categories: " . $res->fetch_assoc()['cnt'] . "\n";

$res = $mysqli->query("SELECT COUNT(*) as cnt FROM work_subcategories");
echo "Remaining subcategories: " . $res->fetch_assoc()['cnt'] . "\n";

echo "Done!";
$mysqli->close();
