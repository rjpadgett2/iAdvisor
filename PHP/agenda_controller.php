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

//All Queries
$currentUser = $_SESSION['email'];
$total_classes = array();
//Gets Current User ID
$current_user_data_query = "SELECT student_id FROM Students WHERE email = '$currentUser';";
$current_user_data_query_results = mysqli_query($connection, $current_user_data_query);
while($line = mysqli_fetch_assoc($current_user_data_query_results)){
  $student_id = $line['student_id'];
}

  $all_classes_query = "SELECT class_id, class_num, class_name, credits FROM Course";
  $all_classes_query_results = mysqli_query($connection, $all_classes_query);
  while($line = mysqli_fetch_assoc($all_classes_query_results)){
    $total_classes[] = $line;
  }
  echo json_encode($total_classes);
?>
