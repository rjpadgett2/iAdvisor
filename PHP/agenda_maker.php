<?php
require_once '../config.php';
//All Queries
$currentUser = $_SESSION['email'];
$total_classes = array();
$class_prereqs = array();

$result = mysql_query("SHOW email FROM Students_has_Course LIKE '$currentUser'");

//Gets List of Prereqs and puts it into JSON
$prereq_query = "SELECT course_class_id, pre_req_of FROM Prerequisite";

//Checks if user email exists in Students_has_Course database
$exists = (mysql_num_rows($result))?TRUE:FALSE;
  if($exists) {
    $all_classes_query = "SELECT class_id, class_num, class_name, credits FROM Course LEFT JOIN Students_has_Course
    ON Course.class_id = Students_has_Course.Course_class_id WHERE Students_has_Course.Course_class_id  IS NULL";

  }else {
    $all_classes_query = "SELECT class_id, class_num, class_name, credits FROM Course";

  }

  $prereq_query_results = mysqli_query($connection, $prereq_query);
  $all_classes_query_results = mysqli_query($connection, $all_classes_query);
  while($line = mysqli_fetch_assoc($all_classes_query_results)){
    $total_classes[] = $line;
  }

  while($line = mysqli_fetch_assoc($prereq_query_results)){
    $class_prereqs[] = $line;
  }
?>
<html lang="en">
<head>
  <link rel="stylesheet" href="../CSS/agenda_maker_style.css?version=10">

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Droppable - Revert draggable position</title>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
    <link href="/jquery-ui-1.12.1.custom/jquery-ui.css" rel="stylesheet">
    <link href="/jquery-ui-1.12.1.custom/jquery-ui.theme.css" rel="stylesheet">
    <script src="/jquery-ui-1.12.1.custom/jquery-ui.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
  <!-- Latest compiled JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">


</head>
<script>
var creditLimit = 0;
var semester;
var credits;
var semesterNumber;
var nextSemester;
var curr_semester_children;
var curr_semester;
var next_semester;
var semesterCredits;


//Turns PHP Array into JSON
var remaining_classes = <?php echo json_encode($total_classes); ?>;
var class_prereqs = <?php echo json_encode($class_prereqs); ?>;
</script>




<body>
  <nav class="navbar navbar-expand-sm navbar-light sticky-top">
    <a class="navbar-brand" href="home.php">
      <img src="../img/iAdvisorLogo.png" alt="Logo" style="width:150px;">
    </a>
   <!-- Toggler/collapsibe Button -->
   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
     <span class="navbar-toggler-icon"></span>
   </button>
         <!-- Navbar links -->
        <div class="collapse navbar-collapse " id="collapsibleNavbar">
          <ul class="nav navbar-nav navbar-right">
           <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li> -->
           <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
         </ul>
        </div>
   </nav>

<!-- Exception Information -->
<nav id = "infoBox" style="display:none;" class="navbar navbar-inverse" data-spy="affix" data-offset-top="197">
 <span class="glyphicon glyphicon-warning-sign"></span>
 <H4 id = "infobox-title">You need special permission to take these classes:</H4>
 <br>
</nav>

<!-- Student 4 year Plan -->
<!-- <div id = "student_schedule" class = "well well-sm"> -->
  <center> <H1 id = "title">Your 4 year Plan<H1></center>
  <div class="btn-group well" role="group" aria-label="Basic example">
    <button type="button" class="btn btn-primary">Save Current Schedule</button>
    <button type="button" onclick="submitClasses()" class="button btn btn-danger">Submit to Advisor</button>
    <button type="button" class="btn btn-success">Reset Schedule</button>
  </div>


  <div class="year-one well">
      <center><h3>Year One</h3></center>
      <div class = "fall_semester well">
        <center><h4>Semester 1</h4></center>
        <center><h6><em>Total Credits: <span class = "credits" id = "semester-one-credits">0</span></em></h6></center>
        <ul id="semester1" class="connectedSortable"></ul>
      </div>
      <div  class = "spring_semester well">
        <center><h4>Semester 2</h4></center>
          <center><h6><em>Total Credits: <span class = "credits" id = "semester-two-credits">0</span></em></h6></center>
        <ul id = "semester2" class = "connectedSortable"></ul>
      </div>
  </div>

  <div class = "year-two well">
      <center><h3>Year Two</h3></center>
      <div  class = "fall_semester well">
        <center><h4>Semester 3</h4></center>
          <center><h6><em>Total Credits: <span class = "credits" id = "semester-three-credits">0</span></em></h6></center>
        <ul id = "semester3" class = "connectedSortable"></ul>
      </div>
      <div  class = "spring_semester well">
        <center><h4>Semester 4</h4></center>
          <center><h6><em>Total Credits: <span class = "credits" id = "semester-four-credits">0</span></em></h6></center>
        <ul id = "semester4" class = "connectedSortable"></ul>
      </div>
  </div>

  <div class = "year-three">
      <center><h3>Year Three</h3></center>
      <div  class = "fall_semester well">
        <center><h4>Semester 5</h4></center>
          <center><h6><em>Total Credits: <span class = "credits" id = "semester-five-credits">0</span></em></h6></center>
        <ul id = "semester5" class = "connectedSortable"></ul>
      </div>
      <div class = "spring_semester well">
        <center><h4>Semester 6</h4></center>
          <center><h6><em>Total Credits: <span class = "credits" id = "semester-six-credits">0</span></em></h6></center>
        <ul id = "semester6" class = "connectedSortable"></ul>
      </div>
  </div>

  <div class = "year-four">
    <center><h3>Year Four</h3></center>
    <div class = "fall_semester well">
      <center><h4>Semester 7</h4></center>
        <center><h6><em>Total Credits: <span class = "credits" id = "semester-seven-credits">0</span></em></h6></center>
      <ul id = "semester7" class = "connectedSortable"></ul>
    </div>
    <div class = "spring_semester well">
      <center><h4>Total Semester 8</h4></center>
      <center><h6><em>Total Credits: <span class = "credits" id = "semester-eight-credits">0</span></em></h6></center>
      <ul id = "semester8" class = "connectedSortable"></ul>
    </div>
  <!-- </div>` -->


</body>
<script src="../JS/agenda_maker.js?version=0.4"> </script>
</html>
