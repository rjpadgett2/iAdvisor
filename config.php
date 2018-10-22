<?php
// For testing Purposes
// $server = "127.0.0.1";
// $username = "root";
// $password = "nilemonitor354";
// $db = "iAdvisor";
// $port = "3306";
//
// $connection = mysqli_connect($server, $username, $password, $db, $port);
//
// // Check connection
// if (!$connection) {
//     die("Connection failed: " . mysqli_connect_error());
// }

// For Cloud Purposes
$server = "35.227.16.244";
$username = "rpadgett";
$password = "iadvisor";
$db = "iAdvisor";
$port = "3306";

$connection = mysqli_connect($server, $username, $password, $db, $port);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}


?>
