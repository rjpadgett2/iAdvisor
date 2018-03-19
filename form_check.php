<?php
$server = "127.0.0.1";
$username = "root";
$password = "nilemonitor354";
$db = "iadvisor";
$port = "3306";

//-----------------------Recieve Input from New_student_form.php------------>
//$year = $_POST['year'];
$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$uidNumber = $_POST['uid'];
$email = $_POST['email'];
$classesTaken = $_POST['classes'];



//-----------------------Connects and Query the Database --------------------->

$connection = mysqli_connect($server, $username, $password, $db, $port);
// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

//Query that retrives abbreviation, class name, class number, and isCore FROM
//                 both the Course table and School table
$info_query = "SELECT
    abbreviation, class_num, class_name, core, credits, class_id
FROM
    Course c
        JOIN
    school s ON c.school_id = s.school_id
ORDER BY class_num";




//This array will hold the names of each class & if they are cores
$classList = array();
$cores = array();
$totalCredits = array();
$cresits = 0;
$info_result = mysqli_query($connection, $info_query);


//Queries the database to get the individual parts of the class names and combine them
if ($info_result->num_rows > 0) {
    while($row = mysqli_fetch_assoc($info_result)) {
    array_push($classList, $row["abbreviation"] . (string)$row["class_num"] . ": " . $row["class_name"]);
    array_push($cores, $row["core"]);
    array_push($totalCredits, $row["credits"].$row["class_name"];
    }
} else {
  //  echo "0 results";
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

//<---------------------------------------------------------------------------->

//checks if submission went through and student is not freshman status
if($_SERVER['REQUEST_METHOD'] == 'POST' && $classesTaken != 0){
  //This array will hold the classes to take. Starts by having a list of every class
  $classesNeeded = $classList;
  $error_message = "Submission Error, Please try Again";
  $success_message = "Submission Success!";




  //Enters User Information into the table
  "INSERT INTO Users (UID, Last Name, First Name,  email)
    VALUES ($uidNumber, $lastName, $firstName, $email)";


  //This loop will eliminate the classes from the list if the user has already completed them
  for ($i=0; $i<sizeof($classesNeeded); $i++){
    if (isCore($classesNeeded[$i], $classList, $cores)){
      if (in_array($classesNeeded[$i], $classesTaken)){
        unset($classesNeeded[$i]);
        $classesNeeded = array_values($classesNeeded);
        $i--;
      }
    }
  }


  //Checks progress status of students
  for($i = 0; $i < sizeof($classesTaken); $i++){
    if(in_array($totalCredits[i]), $classesTaken){
      $credits += 3;
    }
  }

  if($credits <= 30){
    echo "Freshman"
  }else if($credits > 30 && $credits <= 60){
    echo "Sophmore";
  }else if($credits > 60 && $credits <= 90){
    echo "Junior";
  }else if($credits > 90 && $credits <= 120){
    echo "Senior";
  }else{
    echo "Eligible for Graduation";
  }

  echo "Hello ".$firstName." ".$lastName." Here is your status";
  echo "You are a: ".$year."<br><br>";
  echo "You have taken:<br>";
  foreach ($classesTaken as $classes){
  	echo $classes. "<br>";
  }
  echo "<br>You still need to take:<br>";
  foreach ($classesNeeded as $classes){
  	echo $classes. "<br>";
  }


  //header("location: login_screen.html",  true,  301 );  exit;
}else if($_SERVER['REQUEST_METHOD'] == 'POST' && $classesTaken == 0){
  echo("You are a Freshmen");
}else {
  echo("Error no submission detected");
}





?>
