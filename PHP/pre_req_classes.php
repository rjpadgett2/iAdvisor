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
  //Gets List of Prereqs and puts it into JSON
  $prereq_query = "SELECT course_class_id, pre_req_of FROM Prerequisite";
  $prereq_query_results = mysqli_query($connection, $prereq_query);

  while($line = mysqli_fetch_assoc($prereq_query_results)){
    $class_prereqs[] = $line;
  }

  echo json_encode($class_prereqs);
  // echo json_encode($saved_classes);
?>
