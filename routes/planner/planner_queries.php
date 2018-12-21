<?php
//include connection file
require_once '../../connect/config.php';
// require_once '/connect/config.php"';
$db = new dbObj();
$connString =  $db->getConnstring();

$params = $_REQUEST;
$action = $params['action'] !='' ? $params['action'] : '';
$planBuilder = new FourYearPlan($connString);

switch($action) {
 case 'pre_req_lookup':
  $planBuilder->pre_req_lookup();
 break;
 case 'reset_planner':
  $planBuilder->reset_planner();
 break;
 case 'save_classes':
  $planBuilder->save_classes();
 break;
 case 'load_saved_classes':
 	$planBuilder->load_saved_classes();
 break;
 case 'load_pre_reqs':
  $planBuilder->load_pre_reqs();
 break;
 case 'load_classes':
 	$planBuilder->load_classes();
 break;
 case 'umd_load_classes':
 	$planBuilder->umd_load_classes();
 break;
 case 'search_classes':
 	$planBuilder->search_classes();
 break;
 default:
 return;
}

Class FourYearPlan {
 protected $con;
 protected $data = array();
 function __construct($connString) {
   $this->conn = $connString;
 }

 function pre_req_lookup() {
   if(isset($_POST['origin']) && !empty($_POST['origin'])) {
       $curr_class = $_POST['origin'];
       $prereq_query = "SELECT pre_req_of, class_abbreviation, class_name FROM Prerequisite JOIN Course
       ON Course.class_id = Prerequisite.pre_req_of WHERE Prerequisite.class_id = '$curr_class'";
       $query = mysqli_query($this->conn, $prereq_query) or die("database error:". mysqli_error($this->conn));
       while($line = mysqli_fetch_assoc($query)){
         $class_prereqs[] = $line;
       }
       echo json_encode($class_prereqs);
   }
 }


 function reset_planner() {
   $currentUser = $_SESSION['email'];
   $student_id = "";
   $current_user_data_query = "SELECT student_id FROM Students WHERE email = '$currentUser';";
   $current_user_data_query_results = mysqli_query($this->conn, $current_user_data_query) or die("database error:". mysqli_error($this->conn));
   while($line = mysqli_fetch_assoc($current_user_data_query_results)){
     $student_id = $line['student_id'];
   }
   $user_check = mysqli_query($this->conn, "SELECT class_id FROM Student_Course_Assoc WHERE student_id = '$student_id'") or die("database error:". mysqli_error($this->conn));
   $count = $user_check -> num_rows;
   if ($count < 1 ) {
     $exists = "Entry Does not Exist";

   } else {
     $exists = "Data Deleted succesfully";
     $delete_value = "DELETE FROM `Student_Course_Assoc` WHERE student_id = '$student_id'";
     if ($this->conn->query($delete_value ) === TRUE) {
       echo "success";

     }else {
         echo "Error: " . $insert_value . "<br>" . $this->conn->error;
     }
   }
 }

 function save_classes() {
   if(isset($_POST['finishedJSON']) && !empty($_POST['finishedJSON'])) {
     // If session variable is not set it will redirect to login page

     $obj = $_POST['finishedJSON'];
     echo $obj;
     $currentUser = $_SESSION['email'];
     $taken = 0;
     $array = json_decode($obj,true);


     $current_user_data_query = "SELECT student_id FROM Students WHERE email = '$currentUser';";
     $current_user_data_query_results = mysqli_query($this->conn, $current_user_data_query) or die("database error:". mysqli_error($this->conn));
     while($line = mysqli_fetch_assoc($current_user_data_query_results)){
       $student_id = $line['student_id'];
     }

     $user_check = mysqli_query($this->conn, "SELECT class_id FROM Student_Course_Assoc WHERE student_id = '$student_id'") or die("database error:". mysqli_error($this->conn));
     $count = $user_check -> num_rows;
     if ($count < 1 ) {
       $exists = "false";

         foreach($array as $item) {
             $insert_value = "INSERT INTO `Student_Course_Assoc` (student_id, class_id, taken, semester, exceptions)
             VALUES ('$student_id', '".$item['id']."', '".$taken."', '".$item['semester']."' , '".$item['exceptions']."')";
             if ($this->conn->query($insert_value ) === TRUE) {

             }else {
                 echo "Error: " . $insert_value . "<br>" . $this->conn->error;
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
           if ($this->conn->query($update_value ) === TRUE) {
           }else {
               echo "Error: " . $update_value . "<br>" . $this->conn->error;
           }
       }
     }

   }
 }

 function load_classes(){
   $total_classes = array();
   $all_classes_query = "SELECT class_id, class_abbreviation, class_name, credits FROM Course WHERE major = '1' order by class_id";
   $all_classes_query_results = mysqli_query($this->conn, $all_classes_query);
   while($line = mysqli_fetch_assoc($all_classes_query_results)){
     $total_classes[] = $line;
   }
   echo json_encode($total_classes);
 }

 function load_saved_classes() {
     $student_id = array();
     $saved_classes = array();
     $currentUser = $_SESSION['email'];
     $current_user_data_query = "SELECT student_id FROM Students WHERE email = '$currentUser';";
     $current_user_data_query_results = mysqli_query($this->conn, $current_user_data_query);
     while($line = mysqli_fetch_assoc($current_user_data_query_results)){
       $student_id = $line['student_id'];

     }

     $saved_classes_query = "SELECT Student_Course_Assoc.class_id, class_abbreviation, class_name, semester ,credits, exceptions FROM Course JOIN Student_Course_Assoc
     ON Course.class_id = Student_Course_Assoc.class_id WHERE student_id = '$student_id' order by class_id";
     $saved_classes_query_results = mysqli_query($this->conn, $saved_classes_query)or die("database error:". mysqli_error($this->conn));

     while($line = mysqli_fetch_assoc($saved_classes_query_results)){
       $saved_classes[] = $line;
     }

     echo json_encode($saved_classes);
 }

 function load_pre_reqs(){
   //Gets List of Prereqs and puts it into JSON
   $prereq_query = "SELECT class_id, pre_req_of FROM Prerequisite";
   $prereq_query_results = mysqli_query($this->conn, $prereq_query);

   while($line = mysqli_fetch_assoc($prereq_query_results)){
     $class_prereqs[] = $line;
   }

   echo json_encode($class_prereqs);
   // echo json_encode($saved_classes);
 }

 function umd_load_classes(){
   $insert_classes = array();

   $curl = curl_init();
   curl_setopt_array($curl, array(
     CURLOPT_URL => "https://api.umd.io/v0/courses/list",
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_TIMEOUT => 30,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => "GET",
     CURLOPT_HTTPHEADER => array(
       "cache-control: no-cache"
     ),
   ));

   $response = curl_exec($curl);
   $err = curl_error($curl);

   $response = json_decode($response, true); //because of true, it's in an array
   // echo 'Online: '. $response;

   foreach($response as $item) {
      $class_abbreviation = $item['course_id'];
      $class_name = $item['name'];
      $major = 0;
      $credits = 3;

      $add_classes = "INSERT INTO Course (class_abbreviation, class_name, credits, major)
                      VALUES ('".$class_abbreviation."', '".$class_name."', '".$credits."', '".$major."')";
      if ($this->conn->query($add_classes) === TRUE) {
        echo "Success";
      }else {
          echo "Error: " . $add_classes . "<br>" . $this->conn->error;
      }
   }
 }

 function search_classes(){

   ////  End of data collection from table ///
  //  if(isset($_POST['search'])){
  //   $search_val=$_POST['search_term'];
  //   $search_query = "select * from Course where MATCH(class_name) AGAINST('$search_val' IN BOOLEAN MODE)";
  //   $search_query_results = mysqli_query($this->conn, $search_query);
  //   while($line = mysqli_fetch_assoc($search_query_results)){
  //     echo $line;
  //   }
  //   // while($row=mysql_fetch_array($search_query_results)){
  //   //  echo "<li><span class='title'>".$row['class_abbreviation']."</span><br><span class='desc'>".$row['class_name']."</span></a></li>";
  //   // }
  //   exit();
  // }
 }

}

?>
