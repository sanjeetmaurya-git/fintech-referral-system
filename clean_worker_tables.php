<?php
$mysqli = new mysqli('localhost', 'root', '', 'fintech_db');
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$tables = ['jobs', 'worker_documents', 'workers', 'work_subcategories', 'work_categories'];
foreach ($tables as $table) {
    $mysqli->query("DROP TABLE IF EXISTS $table");
}
echo 'Cleaned';
$mysqli->close();
