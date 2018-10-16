<?php
  require_once '../config.php';

  if(isset($_POST['finishedJSON']) && !empty($_POST['finishedJSON'])) {
    session_start();
    // If session variable is not set it will redirect to login page
    if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
      header("location: ../index.php");
      exit;
    }

    $obj = $_POST['finishedJSON'];
    echo $obj;
    $currentUser = $_SESSION['email'];
    $taken = 0;
    $array = json_decode($obj,true);


    $current_user_data_query = "SELECT student_id FROM Students WHERE email = '$currentUser';";
    $current_user_data_query_results = mysqli_query($connection, $current_user_data_query);
    while($line = mysqli_fetch_assoc($current_user_data_query_results)){
      $student_id = $line['student_id'];
    }

    $user_check = mysqli_query($connection, "SELECT class_id FROM Student_Course_Assoc WHERE student_id = '$student_id'");
    $count = $user_check -> num_rows;
    if ($count < 1 ) {
      $exists = "false";

        foreach($array as $item) {
            $insert_value = "INSERT INTO `Student_Course_Assoc` (student_id, class_id, taken, semester, exceptions)
            VALUES ('$student_id', '".$item['id']."', '".$taken."', '".$item['semester']."' , '".$item['exceptions']."')";
            if ($connection->query($insert_value ) === TRUE) {

            }else {
                echo "Error: " . $insert_value . "<br>" . $connection->error;
            }
        }
    } else {
      $exists = "true";
       foreach($array as $item) {
          $value = $item['exceptions'];
          $semester = $item['semester'];
          $id = $item['id'];
          $update_value = "UPDATE `Student_Course_Assoc`
          SET semester = '$semester', exceptions = '$value'
          WHERE class_id = '$id' AND student_id = '$student_id'";
          if ($connection->query($update_value ) === TRUE) {
          }else {
              echo "Error: " . $update_value . "<br>" . $connection->error;
          }
      }
    }

  }

?>
