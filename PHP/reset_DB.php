<?php
  require_once '../config.php';


    session_start();
    // If session variable is not set it will redirect to login page
    $currentUser = $_SESSION['email'];
    $student_id = "";
    $current_user_data_query = "SELECT student_id FROM Students WHERE email = '$currentUser';";
    $current_user_data_query_results = mysqli_query($connection, $current_user_data_query);
    while($line = mysqli_fetch_assoc($current_user_data_query_results)){
      $student_id = $line['student_id'];
    }
    $user_check = mysqli_query($connection, "SELECT class_id FROM Student_Course_Assoc WHERE student_id = '$student_id'");
    $count = $user_check -> num_rows;
    if ($count < 1 ) {
      $exists = "Entry Does not Exist";

    } else {
      $exists = "Data Deleted succesfully";
      $delete_value = "DELETE FROM `Student_Course_Assoc` WHERE student_id = '$student_id'";
      if ($connection->query($delete_value ) === TRUE) {
        echo "success";

      }else {
          echo "Error: " . $insert_value . "<br>" . $connection->error;
      }

    }


    echo $exists;


?>
