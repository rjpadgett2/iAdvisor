<?php
require_once 'config.php';

if(isset($_POST['origin']) && !empty($_POST['origin'])) {
    $number = 1;
    $class_prereqs = array();
    $curr_class = $_POST['origin'];
    $prereq_query = "SELECT pre_req_of FROM Prerequisite WHERE course_class_id = '$curr_class'";
    $query = mysqli_query($connection, $prereq_query);

    while($line = mysqli_fetch_assoc($query)){
      $class_prereqs[] = $line;
    }
    echo json_encode($class_prereqs);
}

?>
