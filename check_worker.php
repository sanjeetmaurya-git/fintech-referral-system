<?php
$mysqli = new mysqli('localhost', 'root', '', 'fintech_db');
$res = $mysqli->query("SELECT * FROM workers WHERE user_id = 5");
$row = $res->fetch_assoc();
if ($row) {
    echo "Is Worker: Yes, Status: " . $row['status'];
} else {
    echo "Is Worker: No";
}
$mysqli->close();
