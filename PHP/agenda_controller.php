<?php

//Loade Class Into Database and initializes Session
require_once '../config.php';

// Initialize the session
session_start();
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
  header("location: ../index.php");
  exit;
}

$total_classes = array();

$all_classes_query = "SELECT class_id, class_num, class_name, credits, abbreviation FROM Course JOIN Departments
WHERE Course.department_id = Departments.department_id order by Course.class_id";
$all_classes_query_results = mysqli_query($connection, $all_classes_query);
while($line = mysqli_fetch_assoc($all_classes_query_results)){
  $total_classes[] = $line;
}
echo json_encode($total_classes);
?>
