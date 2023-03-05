<?php

// Database host
$db_host = "localhost";
// Username
$db_username = "zach";
// User Password
$db_password = "test1234";
// Databased name
$db_name = "loginreg";

// Connection to database - PDO library allows for extra security when connecting to database to prevent hackers highjacking database
try {
    $db = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_username, $db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
// Catch errors
catch (PDOException $e) {
    echo $e->getMessage();
}
