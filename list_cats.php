<?php
$mysqli = new mysqli('localhost', 'root', '', 'fintech_db');
$res = $mysqli->query("SELECT id, name FROM work_categories");
while($row = $res->fetch_assoc()) {
    echo "CAT: " . $row['id'] . " - " . $row['name'] . "\n";
}
$res = $mysqli->query("SELECT id, category_id, name FROM work_subcategories");
while($row = $res->fetch_assoc()) {
    echo "SUBCAT: " . $row['id'] . " (CAT " . $row['category_id'] . ") - " . $row['name'] . "\n";
}
$mysqli->close();
