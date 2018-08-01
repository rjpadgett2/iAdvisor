<?php
require_once 'config.php';
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
</head>

<pre id = "test"></pre>

  <script>

//Turns PHP Array into JSON
var remaining_classes = <?php echo json_encode($total_classes); ?>;
var class_prereqs = <?php echo json_encode($class_prereqs); ?>;

//Test to see if JSON contains values. Prints JSON
// document.getElementById("test").innerHTML = JSON.stringify(remaining_classes, undefined, 2);
// document.getElementById("test").innerHTML = JSON.stringify(class_prereqs, undefined, 2);

  $( function() {
   $( "#semester1, #semester2, #semester3, #semester4, #semester5, #semester6, #semester7, #semester8").sortable({
     connectWith: ".connectedSortable"
   }).disableSelection();
} );


$(document).ready(function(){
//All global variables
    var creditLimit = 0;
    var semester;
    var semesterNumber;
    var season;
    var totalCredits;
    var nextSemester;
    var curr_semester_children;
    var curr_semester
    var next_semester;



//Iterate through JSOn and compares values
    $.each(remaining_classes, function(i, items) {
        var credits = parseInt(items.credits);


                creditLimit += parseInt(items.credits);
                if(creditLimit <= 15){
                  semester = 'semester1';
                  season = 0;
                  nextSemester = 'semester2';
                }else if(creditLimit > 15 && creditLimit <= 30){
                  semester = 'semester2';
                  season = 1;
                  nextSemester = 'semester3';
                }else if(creditLimit > 30 && creditLimit <= 45){
                  semester = 'semester3';
                  season = 0;
                  nextSemester = 'semester4';
                }else if(creditLimit > 45 && creditLimit <= 60){
                  semester = 'semester4';
                  season = 1;
                  nextSemester = 'semester5';
                }else if(creditLimit > 60 && creditLimit <= 75){
                  semester = 'semester5';
                  season = 0;
                  nextSemester = 'semester6';
                }else if(creditLimit > 75 && creditLimit <= 90){
                  semester = 'semester6';
                  season = 1;
                  nextSemester = 'semester7';
                }else if(creditLimit > 90 && creditLimit <= 105){
                  semester = 'semester7';
                  season = 0;
                  nextSemester = 'semester8';
                }else if(creditLimit > 105 && creditLimit <= 120){
                  semester = 'semester8';
                  season = 1;
                }

                next_semester_id =  document.getElementById(nextSemester);
                curr_semester = document.getElementById(semester);
                curr_semester_children = document.getElementById(semester).children;

                //Checks Pre-Reqs and moves class to next semester if current semester contains classes
                //contains that are pre-reqs of selected class
                for (var i = 0; i < curr_semester_children.length; i++) {
                 var curr_class = curr_semester_children[i];
                 var curr_class_id = curr_class.id;
                 for(var j = 0; j < curr_semester_children.length; j++){
                   var other_classes_id = curr_semester_children[j].id;
                   var other_classes = curr_semester_children[j];
                   $.each(class_prereqs, function(j, pre){
                     if(other_classes_id == pre.pre_req_of && curr_class_id == pre.course_class_id && other_classes_id != curr_class_id){
                       if($.inArray(other_classes, curr_semester_children) != -1){
                         next_semester_id.appendChild(other_classes);
                       }

                     }
                   });
                 }
                }
                if(season == 0){
                  $(curr_semester).append('<li id = "'+items.class_id+'" class="ui-state-default">' + items.class_num + " " + items.class_name + '</li>');
                }else if(season == 1){
                  $(curr_semester).append('<li id = "'+items.class_id+'" class="ui-state-highlight">' + items.class_num + " " + items.class_name + '</li>');
                }
                //Changes color of list element based on semester fall/spring

      })

      // semester = '#semester1';
      // nextSemester = 'semester2'
      // $(semester+' li').each(function () {
      //   var class_check_id = this.id;
      //   var class_check_li = this;
      //   var ul = document.getElementById(nextSemester);
      //   $.each(class_prereqs, function(j, pre){
      //     if(class_check_id == pre.pre_req_of){
      //       ul.appendChild(class_check_li);
      //       $(class_check_li).parent().next('ul').appendChild(class_check_id);
      //     }
      //   });
      // });

      //Gets information about the class standing of the student
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

      //Makes Year Div dissapear if there are no classes being taken that year
      if ($('#semester1 li').length == 0 && $('#semester2 li').length == 0){
        $('#yearOne').hide();
      } else if ($('#semester3 li').length == 0 && $('#semester4 li').length == 0){
        $('#yearTwo').hide();
      } else if ($('#semester5 li').length == 0 && $('#semester6 li').length == 0){
        $('#yearThree').hide();
      } else if ($('#semester7 li').length == 0 && $('#semester8 li').length == 0){
        $('#yearFour').hide();
      }

  var items = [];
  function onReceive(event, ui) {
    //Grabs ul identifier of target ul
    var receiver = event.target;
    //ID of list elemtent being dragged
    var origin = ui.item.attr('id');

    //Ajax call that returns list pf pre-reqs from database
      $.ajax({
          url:"pre_req_lookup.php",
          type: 'POST',
          data: {origin: origin},
          success: function(data){
            output = JSON.parse(data);
            for(var i = 0; i < output.length; i++){
              //iterates through UL and checks li ID's
              $(receiver).find('li').each(function(j){
                //prits type of values being compared
                  if (parseInt($(this).attr("id")) == parseInt(output[i]["pre_req_of"])) {
                    console.log("yes:" + $(this).attr("id"));
                    // changes color of Li of dragged element to Red to indicate pre_req error
                    document.getElementById(origin).className = "ui-state-error";
                  }
                });
            }
          },
        });
        console.log(items);
    // if (!isAllowed) {
    //   $(ui.sender).sortable("cancel");
    // }
  }

  $(function() {
    var opts = {
      connectWith: ".connectedSortable",
      receive: onReceive
    };
    $('.connectedSortable').sortable(opts).disableSelection();
    $('.connectedSortable').find('li').attr('id', function() {
      return $(this).attr('id');
    });
  });

});
</script>




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
      <center><h3>Year One</h3></center>
      <div class = "student_semester">
        <center><h4>Semester 1</h4></center>
        <ul id="semester1" class="connectedSortable" data-allow-from="#semester1" ></ul>
      </div>
      <div  class = "student_semester">
        <center><h4>Semester 2</h4></center>
        <ul id = "semester2" class = "connectedSortable" data-allow-from="#semester1, #semester2"></ul>
      </div>
  </div>

  <div id = "yearTwo">
      <center><h3>Year Two</h3></center>
      <div  class = "student_semester">
        <center><h4>Semester 3</h4></center>
        <ul id = "semester3" class = "connectedSortable"></ul>
      </div>
      <div  class = "student_semester">
        <center><h4>Semester 4</h4></center>
        <ul id = "semester4" class = "connectedSortable"></ul>
      </div>
  </div>

  <div id = "yearThree">
      <center><h3>Year Three</h3></center>
      <div  class = "student_semester">
        <center><h4>Semester 5</h4></center>
        <ul id = "semester5" class = "connectedSortable"></ul>
      </div>
      <div class = "student_semester">
        <center><h4>Semester 6</h4></center>
        <ul id = "semester6" class = "connectedSortable"></ul>
      </div>
  </div>

  <div id = "yearFour">
    <center><h3>Year Four</h3></center>
    <div class = "student_semester">
      <center><h4>Semester 7</h4></center>
      <ul id = "semester7" class = "connectedSortable"></ul>
    </div>
    <div class = "student_semester">
      <center><h4>Semester 8</h4></center>
      <ul id = "semester8" class = "connectedSortable"></ul>
    </div>
  </div>`
</div>

<!-- <div id = "infoBox">
  <h1>This is America </h1>

</div> -->



</body>
</html>
