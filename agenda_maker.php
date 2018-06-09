<?php

require_once 'config.php';
//All Queries
$currentUser = $_SESSION['email'];

$result = mysql_query("SHOW email FROM Students_has_Course LIKE '$currentUser'");
$exists = (mysql_num_rows($result))?TRUE:FALSE;
if($exists) {
  $taken_classes_query = "SELECT class_num, class_id
    FROM Students_has_Course JOIN Course ON Students_has_Course.Course_class_id = Course.class_id";

  $all_classes_query = "SELECT class_id, class_num, class_name, credits FROM Course LEFT JOIN Students_has_Course
  ON Course.class_id = Students_has_Course.Course_class_id WHERE Students_has_Course.Course_class_id  IS NULL";
  $taken_classes_query_results = mysqli_query($connection, $taken_classes_query);

  $all_classes_query_results = mysqli_query($connection, $all_classes_query);

  $student_remaining_classes = array();
  $student_taken_classes = array();
  $total_classes = array();

  while($row = mysqli_fetch_assoc($taken_classes_query_results)){
    $student_taken_classes[$row['class_id']] = $row['class_num'];
  }
  while($line = mysqli_fetch_assoc($all_classes_query_results)){
    $total_classes[] = $line;
  }
}else {
  $all_classes_query = "SELECT class_id, class_num, class_name, credits FROM Course";
  $all_classes_query_results = mysqli_query($connection, $all_classes_query);

  $total_classes = array();
  while($line = mysqli_fetch_assoc($all_classes_query_results)){
  $total_classes[] = $line;
  }
}





// $subtotal_classes = array_diff_key($student_remaining_name_classes, $student_taken_classes);

?>
<html lang="en">
<head>

  <link rel="stylesheet" href="agenda_maker_style.css">

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Droppable - Revert draggable position</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
    <link href="jquery-ui-1.12.1.custom/jquery-ui.css" rel="stylesheet">
    <link href="jquery-ui-1.12.1.custom/jquery-ui.theme.css" rel="stylesheet">
    <script src="jquery-ui-1.12.1.custom/jquery-ui.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
  <!-- Latest compiled JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">


  <script>
  $( function() {
   $( "#semester1, #semester2, #semester3, #semester4, #semester5, #semester6, #semester7, #semester8").sortable({
     connectWith: ".connectedSortable"
   }).disableSelection();

   $('ul').sortable({
          connectWith: 'ul',
          beforeStop: function(ev, ui) {
              if ($(ui.item).hasClass('number') && $(ui.placeholder).parent()[0] != this) {
                  $(this).sortable('cancel');
              }
          }
      });

  } );

  // # this is an AJAX function that check whether a course can be inserted.


  var remaining_classes = <?php echo json_encode($total_classes); ?>;
  var taken_classes = <?php echo json_encode($student_taken_classes); ?>;



  $(document).ready(function(){
    //Test to see if JSON contains values. Prints JSON
    // document.getElementById("test").innerHTML = JSON.stringify(taken_classes, undefined, 2);
    var remaining_classes2 = JSON.stringify(remaining_classes, undefined, 2);
    // document.getElementById("test").innerHTML = remaining_classes2;
    var creditLimit = 0;
    var semester;
    var semesterNumber;
    var season;
    var totalCredits;

    $.each(remaining_classes, function(i, items) {
    var credits = parseInt(items.credits);

    creditLimit += parseInt(items.credits);
    if(creditLimit <= 15){
      semester = '#semester1';
      season = 0;
      semesterNumber
    }else if(creditLimit > 15 && creditLimit <= 30){
      semester = '#semester2';
      season = 1;
    }else if(creditLimit > 30 && creditLimit <= 45){
      semester = '#semester3';
      season = 0;
    }else if(creditLimit > 45 && creditLimit <= 60){
      semester = '#semester4';
      season = 1;
    }else if(creditLimit > 60 && creditLimit <= 75){
      semester = '#semester5';
      season = 0;
    }else if(creditLimit > 75 && creditLimit <= 90){
      semester = '#semester6';
      season = 1;
    }else if(creditLimit > 90 && creditLimit <= 105){
      semester = '#semester7';
      season = 0;
    }else if(creditLimit > 105 && creditLimit <= 120){
      semester = '#semester8';
      season = 1;
    }



    if(season == 0){
      $(semester).append('<li id = "'+items.class_id+'" class="ui-state-default">' + items.class_num + " " + creditLimit + " " + items.class_name + '</li>');
    }else if(season == 1){
      $(semester).append('<li id = "'+items.class_id+'" class="ui-state-highlight">' + items.class_num + " " + creditLimit + " " + items.class_name + '</li>');
    }


  });

    switch (semesterNumber){
      case 1:
          year = "Freshman";
          break;
      case 2:
          year = "Freshman";
          break;
      case 3:
          year = "Sophmore";
          break;
      case 4:
          year = "Sophmore";
          break;
      case 5:
          year = "Junior";
          break;
      case 6:
          year = "Junior";
          break;
      case 7:
          year = "Senior";
          break;
      case 8:
          year = "Senior";
          break;
      case semester > 8:
          year = "Eligible For Graduation";
          break;
    }

  });
</script>


</head>

<body>

  <nav class="navbar navbar-expand-sm navbar-light sticky-top">
    <a class="navbar-brand" href="#">
      <img src="img/iAdvisorLogo.png" alt="Logo" style="width:150px;">
    </a>
   <!-- Toggler/collapsibe Button -->
   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
     <span class="navbar-toggler-icon"></span>
   </button>


         <!-- Navbar links -->
        <div class="collapse navbar-collapse " id="collapsibleNavbar">
          <ul class="nav navbar-nav">
            <li>
              <a href="home.php"><span class="glyphicon glyphicon-home"></span>Home</a>
            </li>
          </ul>

          <ul class="nav navbar-nav navbar-right">
           <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li> -->
           <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
         </ul>
        </div>
   </nav>

  <center><H1>Your 4 year Plan<H1></center>


<div id = "agenda_template">
  <div id = "yearOne">
      <center><h1>Year One</h1></center>
      <center><h3>Semester 1</h3></center>
      <ul id="semester1" class="connectedSortable" ></ul>
      <center><h3>Semester 2</h3></center>
      <ul id = "semester2" class = "connectedSortable"></ul>
  </div>

  <div id = "yearTwo">
      <center><h1>Year Two</h1></center>
      <center><h3>Semester 3</h3></center>
      <ul id = "semester3" class = "connectedSortable"></ul>
      <center><h3>Semester 4</h3></center>
      <ul id = "semester4" class = "connectedSortable"></ul>
  </div>

  <div id = "yearThree">
      <center><h1>Year Three</h1></center>
      <center><h3>Semester 5</h3></center>
      <ul id = "semester5" class = "connectedSortable"></ul>
      <center><h3>Semester 6</h3></center>
      <ul id = "semester6" class = "connectedSortable"></ul>
  </div>

  <div id = "yearFour">
    <center><h1>Year Four</h1></center>
    <center><h3>Semester 7</h3></center>
    <ul id = "semester7" class = "connectedSortable"></ul>
    <center><h3>Semester 8</h3></center>
    <ul id = "semester8" class = "connectedSortable"></ul>
  </div>`
</div>

<div id = "student_agenda">
  <div id = "yearOne">
      <center><h1>Year One</h1></center>
      <center><h3>Semester 1</h3></center>
      <ul id="semester1" class="connectedSortable" ></ul>
      <center><h3>Semester 2</h3></center>
      <ul id = "semester2" class = "connectedSortable"></ul>
  </div>

  <div id = "yearTwo">
      <center><h1>Year Two</h1></center>
      <center><h3>Semester 3</h3></center>
      <ul id = "semester3" class = "connectedSortable"></ul>
      <center><h3>Semester 4</h3></center>
      <ul id = "semester4" class = "connectedSortable"></ul>
  </div>

  <div id = "yearThree">
      <center><h1>Year Three</h1></center>
      <center><h3>Semester 5</h3></center>
      <ul id = "semester5" class = "connectedSortable"></ul>
      <center><h3>Semester 6</h3></center>
      <ul id = "semester6" class = "connectedSortable"></ul>
  </div>

  <div id = "yearFour">
    <center><h1>Year Four</h1></center>
    <center><h3>Semester 7</h3></center>
    <ul id = "semester7" class = "connectedSortable"></ul>
    <center><h3>Semester 8</h3></center>
    <ul id = "semester8" class = "connectedSortable"></ul>
  </div>`
</div>

</body>
</html>
