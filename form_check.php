<?php
$server = "127.0.0.1";
$username = "root";
$password = "nilemonitor354";
$db = "iadvisor";
$port = "3306";

//-----------------------Recieve Input from new_student_form.php------------>
$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$uidNumber = $_POST['uid'];
$email = $_POST['email'];
$classesTaken = $_POST["classes"];

//Dispays Code Errors on Page
ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);


//-----------------------Connects and Query the Database --------------------->
$connection = mysqli_connect($server, $username, $password, $db, $port);
// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

//-----------------------Queries to retrive data from database----------------->

//Enters User Information into the table
$user_insert_query = "INSERT INTO Students
  VALUES ('$uidNumber', '$lastName', '$firstName', '$email')";

  if ($connection->query($user_insert_query) === TRUE) {
    echo "User Personal Data created successfully";
  } else {
      echo "Error: " . $connection->error;
  }

//Enters student uidNumber and Classes taken
  foreach($classesTaken as $x=> $x_value){

    $number_of_entries = 0;
    $user_classes_insert_query =   "INSERT INTO Students_has_Course
      VALUES ('$uidNumber','$x')";
      if ($connection->query($user_classes_insert_query) === TRUE) {
        $number_of_entries += 1;
        echo $number_of_entries;
      } else {
          echo "Error: " . $connection->error;
          break;
      }
  }

$info_query = "SELECT
    abbreviation, class_num, class_name, core, credits, class_id
FROM
    Course c
        JOIN
    school s ON c.school_id = s.school_id
ORDER BY class_num";

$class_content = "SELECT class_id, class_name FROM Course";

$class_and_credits = "SELECT class_id, credits FROM Course";

$class_and_credits_result = mysqli_query($connection, $class_and_credits);
$class_content_result = mysqli_query($connection, $class_content);


//----------------------Variable Instantion------------------------------------>

//Arrays
$classList = array();
$cores = array();
$classCredits = array();
$credits = 0;
$class = array();
$takenCredits = array();
$classList_ids = array();
$semester = 0;

$error_message = "Submission Error, Please try Again";
$success_message = "Submission Success!";


//Queries the database to get the individual parts of the class names and combine them
// if ($info_result->num_rows > 0) {
//     while($row = mysqli_fetch_assoc($info_result)) {
//     // array_push($classList, $row["abbreviation"] . (string)$row["class_num"] . ": " . $row["class_name"]);
//     array_push($cores, $row["core"]);
//     // array_push($credits, $row["credits"]);
//     array_push($class, $row["class_num"]);
//     array_push($classCredits, $row["credits"]." ".$row["class_name"]);
//     }
// } else {
//     echo "0 results";
// }

//Queries DB to get total Class list
while($row = mysqli_fetch_assoc($class_content_result)){
  $classList[$row['class_id']] = $row['class_name'];
}
//Queries DB and adds Class Number and Credits to $classCredits Array
while($row = mysqli_fetch_assoc($class_and_credits_result)){
  $classCredits[$row['class_id']] = $row['credits'];
}

//Count Credits and Predict Year
$takenCredits = array_intersect_key($classCredits, $classesTaken);
foreach($takenCredits as $x=> $x_value){
  $credits += $x_value;
}

//Creates array of classes needed based on keys of input array that differ
$classesNeeded = array_diff_key($classList, $classesTaken);

//Determines Semester value based on credits
switch ($credits){
  case $credits <= 15:
      $semester = 1;
      break;
  case $credits > 15 and $credits <= 30:
      $semester = 2;
      break;
  case $credits > 30 and $credits <= 45:
      $semester = 3;
      break;
  case $credits > 45 and $credits <= 60:
      $semester = 4;
      break;
  case $credits > 60 and $credits <= 75:
      $semester = 5;
      break;
  case $credits > 75 and $credits <= 90:
      $semester = 6;
      break;
  case $credits > 90 and $credits <= 105:
      $semester = 7;
      break;
  case $credits > 105:
      $semester = 8;
      break;
}

//Determine year based on Semester
switch ($semester){
  case 1:
      $year = "Freshman";
      break;
  case 2:
      $year = "Freshman";
      break;
  case 3:
      $year = "Sophmore";
      break;
  case 4:
      $year = "Sophmore";
      break;
  case 5:
      $year = "Junior";
      break;
  case 6:
      $year = "Junior";
      break;
  case 7:
      $year = "Senior";
      break;
  case 8:
      $year = "Senior";
      break;
  case $semester > 8:
      $year = "Eligible For Graduation";
      break;
}


//Adds ID class refrences to array
foreach($classesTaken as $class_id => $class_name){
  array_push($classList_ids, $class_id);
}


//Function for troubleshooting
function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);
  //  echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}


//Function to see if a class is a core class or not
function isCore($className, $classList, $cores){
  if (in_array($className, $classList)){
    $index = array_search($className, $classList);
    if ($cores[$index] > 0){
      return true;
    }
  }
  else {
    return false;
  }
}

//Turns array in to strings
// $serialized_classList_ids = serialize($classList_ids);
// $user_profile_insert = "INSERT INTO Course_has_Users
//   VALUES ('1', '$uidNumber', '$serialized_classList_ids', '$semester')";
//   if ($connection->query($user_profile_insert) === TRUE) {
//     echo "User Profile created successfully";
//   } else {
//       echo "Error: " . $connection->error;
//   }


//<----------------------Printed Results--------------------------------------->

  echo "<br>";
  echo "<br>";
  echo "Hello <strong>".$firstName." ".$lastName."</strong> Here is your status<br>";
  echo "<br>";
  foreach($classList_ids as $id){
    echo $id;
  }
  echo "<br>";
  echo "You have taken:<br>";
  foreach($classesTaken as $class_id => $class_name){
    echo "<strong>".$class_name."</strong><br>";
  }
  echo "You have <strong>".$credits."</strong> credits and you are a <STRONG>".$year."</STRONG><br>";
  echo "<br>";
  echo "<br>You still need to take:<br>";
  foreach ($classesNeeded as $class_id => $class_name){
  	echo "<STRONG>".$class_name."</STRONG><br>";

  }

   // echo "<script>window.location = 'index.html'</script>";



?>
