<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(__DIR__);
require __DIR__ . '/app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
if (!defined('ENVIRONMENT')) define('ENVIRONMENT', 'development');
CodeIgniter\Boot::bootConsole($paths);

$db = \Config\Database::connect();
$db->query("UPDATE users SET referred_by = 1 WHERE id = 10");
echo "Updated User 10 to be referred by User 1.\n";
