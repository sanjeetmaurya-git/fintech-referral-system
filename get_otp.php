<?php
$mysqli = new mysqli('localhost', 'root', '', 'fintech_db');
$res = $mysqli->query("SELECT otp_code FROM otps WHERE phone = '9990000005' ORDER BY id DESC LIMIT 1");
$row = $res->fetch_assoc();
echo "OTP: " . $row['otp_code'];
$mysqli->close();
