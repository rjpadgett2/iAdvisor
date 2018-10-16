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
  $current_user_data_query = "SELECT student_id FROM Students WHERE email = '$currentUser';";
  $current_user_data_query_results = mysqli_query($connection, $current_user_data_query);
  while($line = mysqli_fetch_assoc($current_user_data_query_results)){
    $student_id = $line['student_id'];
  }

  $saved_classes_query = "SELECT Student_Course_Assoc.class_id, class_num, class_name, semester ,credits, exceptions FROM Course JOIN Student_Course_Assoc
  ON Course.class_id = Student_Course_Assoc.class_id WHERE student_id = '$student_id'";
  $saved_classes_query_results = mysqli_query($connection, $saved_classes_query);

  while($line = mysqli_fetch_assoc($saved_classes_query_results)){
    $saved_classes[] = $line;
  }

  echo json_encode($saved_classes);
?>
