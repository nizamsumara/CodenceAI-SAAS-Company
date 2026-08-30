<?php

$host = "sql312.infinityfree.com";
$username = "if0_42764860";
$password = "Nizam0678";
$dbname = "if0_42764860_saas__db";

$conn = new mysqli($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>