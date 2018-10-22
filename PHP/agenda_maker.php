<?php
// require_once 'agenda_controller.php';
?>
<html lang="en">
<head>
  <link rel="stylesheet" href="../CSS/agenda_maker_style.css?version=13">


  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Droppable - Revert draggable position</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

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

  <script src="../JS/agenda_maker.js?version=1.5"> </script>

</head>

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
           <li><a href=""><span class="glyphicon glyphicon-user"></span> <?php echo htmlspecialchars($currentUser); ?></a></li>
           <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
         </ul>
        </div>
   </nav>

<!-- Exception Information -->
<div id = "infoBox"  class="navbar navbar-inverse" data-spy="affix" data-offset-right="197">
 <span class="glyphicon glyphicon-warning-sign"></span>
</div>

<!-- Class Info Modal -->
  <div class="modal fade" id="infoModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <center><h4 class="modal-title"></h4></center>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">General Info</a></li>
            <li><a data-toggle="tab" href="#menu1">Grading Method</a></li>
            <li><a data-toggle="tab" href="#menu2">Pre Reqs</a></li>
            <li><a data-toggle="tab" href="#menu3">Sections</a></li>
          </ul>

          <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
              <h3>General Info</h3>
              <p id = "gen_info"></p>
            </div>
            <div id="menu1" class="tab-pane fade">
              <h3>Grading Method</h3>
              <p id = "grading_method"></p>
            </div>
            <div id="menu2" class="tab-pane fade">
              <h3>Pre Reqs</h3>
              <p id = "pre_reqs"></p>
            </div>
            <div id="menu3" class="tab-pane fade">
              <h3>Sections</h3>
              <p id = "sections"></p>
            </div>
          </div>
        </div>
        </div>
      </div>

    </div>
  </div>

<!-- Student 4 year Plan -->
<!-- <div id = "student_schedule" class = "well well-sm"> -->
  <center> <H1 id = "title">Your 4 year Plan<H1></center>
  <div class="alert alert-info">
    <strong>Attention!</strong> Click on Individual Classes to get more Information(Only works for InfoSci classes for now).
  </div>
  <div class="btn-group well" role="group" aria-label="Basic example">
    <button type="button" onclick="addDataToDB()" class="btn btn-primary">Save Current Schedule</button>
    <button type="button" onclick="submitClasses()" class="button btn btn-danger">Submit to Advisor</button>
    <button type="button" onclick="resetDB()" class="btn btn-success">Reset Schedule</button>
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

</html>
