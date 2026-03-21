<?php
$mysqli = new mysqli('localhost', 'root', '', 'fintech_db');

// ---- Fix duplicate categories ----
// Keep only first 6 categories (IDs 1-6)
$mysqli->query("DELETE FROM work_subcategories WHERE category_id > 6");
$mysqli->query("DELETE FROM work_categories WHERE id > 6");

// Check categories now
$res = $mysqli->query("SELECT id, name FROM work_categories");
echo "=== Categories ===\n";
while($row = $res->fetch_assoc()) {
    echo $row['id'] . ": " . $row['name'] . "\n";
}

$res = $mysqli->query("SELECT id, category_id, name FROM work_subcategories");
echo "\n=== Subcategories ===\n";
while($row = $res->fetch_assoc()) {
    echo $row['id'] . " (CAT " . $row['category_id'] . "): " . $row['name'] . "\n";
}

// ---- Check migrations table to confirm worker tables were created ----
$res = $mysqli->query("SHOW TABLES LIKE 'workers'");
echo "\n=== Workers table: " . ($res->num_rows > 0 ? 'EXISTS' : 'MISSING') . " ===\n";

// ---- Confirm registration fields for user 5 (test user) ----
$res = $mysqli->query("SELECT id, phone FROM users WHERE id = 5");
$user = $res->fetch_assoc();
echo "\n=== Test User: " . ($user ? $user['phone'] : 'NOT FOUND') . " ===\n";

// ---- Check if profile exists ----  
$res = $mysqli->query("SELECT id, full_name, email FROM user_profiles WHERE user_id = 5");
$profile = $res->fetch_assoc();
echo "Profile: " . ($profile ? $profile['full_name'] . " / " . $profile['email'] : 'NONE') . "\n";

echo "\nAll checks complete!\n";
$mysqli->close();
