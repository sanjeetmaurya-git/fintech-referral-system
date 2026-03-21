<?php
$mysqli = new mysqli('localhost', 'root', '', 'fintech_db');
$res = $mysqli->query("DESCRIBE users");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
$mysqli->close();
