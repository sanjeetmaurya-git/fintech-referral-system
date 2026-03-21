<?php
require 'app/Config/Database.php';
$config = new \Config\Database();
$db = \CodeIgniter\Database\Config::connect();

try {
    $db->query("ALTER TABLE users DROP COLUMN is_premium");
    echo "Column is_premium dropped successfully.\n";
} catch (\Exception $e) {
    echo "Error or Column already dropped: " . $e->getMessage() . "\n";
}
