<?php
// This script runs via the XAMPP web server where MySQL is accessible
$mysqli = new mysqli('127.0.0.1', 'root', '', 'fintech_db', 3306);
if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error);
}

echo "<pre>\n";

// Remove duplicate categories (keep only IDs 1-6)
$mysqli->query("SET FOREIGN_KEY_CHECKS=0");
$r1 = $mysqli->query("DELETE FROM work_subcategories WHERE category_id > 6");
echo "Deleted subcategory dupes: " . $mysqli->affected_rows . "\n";

$r2 = $mysqli->query("DELETE FROM work_categories WHERE id > 6");
echo "Deleted category dupes: " . $mysqli->affected_rows . "\n";

$mysqli->query("SET FOREIGN_KEY_CHECKS=1");

// Verify
$res = $mysqli->query("SELECT id, name, icon FROM work_categories");
echo "\n=== Categories ===\n";
while($row = $res->fetch_assoc()) {
    echo $row['id'] . ": " . $row['name'] . "\n";
}

$res = $mysqli->query("SELECT id, category_id, name FROM work_subcategories");
echo "\n=== Subcategories ===\n";
while($row = $res->fetch_assoc()) {
    echo "  " . $row['id'] . " (cat " . $row['category_id'] . "): " . $row['name'] . "\n";
}

// Check worker registrations
$res = $mysqli->query("SELECT COUNT(*) as cnt FROM workers");
echo "\n=== Workers registered: " . $res->fetch_assoc()['cnt'] . " ===\n";

// Confirm user 5 exists
$res = $mysqli->query("SELECT id, phone FROM users WHERE id = 5");
$user = $res->fetch_assoc();
echo "Test user exists: " . ($user ? 'YES - ' . $user['phone'] : 'NO') . "\n";

echo "\n<a href='http://localhost/fintech-referral-system/public/worker/register'>Test Registration →</a>";
echo "\n</pre>";
$mysqli->close();
