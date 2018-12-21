<?php
include_once("../partials/header.php");
?>
  <link rel="stylesheet" href="../styles/agenda_maker_style.css?version=0.6">
    <script src="../scripts/agenda_maker.js?version=0.4"> </script>
      <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
</head>

<body>
  <div class="topnav">
    <a class="nav-buttons active" href="../index.php">
      <img src="../img/iAdvisorLogo.png" alt="Logo" style="width:100px;">
    </a>
    <?php
    if(isset($_SESSION['email'])){?>
      <div class="topnav-right">
        <a class="nav-buttons" href = "../about.html"><i class="fa fa-fw fa-info-circle" aria-hidden="true"></i>About</a>
        <a class="nav-buttons" href = "https://uachieve.umd.edu/"><i class="fa fa-fw fa-star-o" aria-hidden="true"></i>UAchieve</a>
        <a class="nav-buttons" href = ""><i class="fa fa-fw fa-question" aria-hidden="true"></i>Help</a>
        <a class="nav-buttons" href="../routes/login/student_login.php?action=logout"><i class="fa fa-fw fa-sign-in"></i>Logout</a>
     </div>
    <?php }
   else{
     ?>
      <a id = "register-btn" class="nav-buttons">Register</a>
      <a id = "student-btn" class="nav-buttons"><i class="fa fa-fw fa-user"></i>Login</a>
    <?php } ?>
 </div>

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
            <div id= "menu1" class="tab-pane fade">
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


<!-- <div id = "student_schedule" class = "well well-sm"> -->

<div id = "menu_items" class = "">
  <div id = "search_box">
    <span class="icon"><i class="fa fa-search"></i></span>
    <form name = "myForm">
      <input type=hidden name=st value=0>
      <input list="title"  type="text" class="search" id="search_term" name = "search_term" placeholder="Search for Classes..." onkeyup="doSearch('');"/>
    </form>
  </div>
  <div class="btn-group" role="group" aria-label="Basic example">
    <button type="button" onclick="submitClasses()" id = "submit-btn" class="funct-btns">Submit</button>
    <button type="button" onclick="addDataToDB()" id = "save-btn" class="funct-btns">Save</button>
    <button type="button" onclick="resetDB()" id = "note-btn" class="funct-btns">Leave Note</button>
    <button type="button" onclick="resetDB()" id = "reset-all-btn" class="funct-btns">Reset All</button>
  </div>
</div>
<!-- Student 4 year Plan -->
<div class = "content">
  <div id = "search-div" class = "">
    <!-- <div id=msg style="position:absolute; width:300px; height:25px;
    z-index:1; left: 400px; top: 0px;
    border: 1px none #000000"></div> -->


    <datalist id="class_results" ></datalist>

    <div id="search_result">
      <ul id = "search_result_list"></ul>
    </div>

    <div class='t1' id='navigation'></div>
  </div>
  <div class = "four-year-plan">
    <div class = "all-years">
    <div class="year-one year">
        <div class = "year-div">
          <center><h3>Year One</h3></center>
        </div>
        <div class = "fall_semester">
            <button type="button" id = "reset-one" class="reset-btn">Reset</button>
            <h4>Semester 1</h4>
            <h6 class = "credits_title"><em>Credits: <span class = "credits" id = "semester-one-credits">0</span></em></h6>
            <ul id="semester1" class="connectedSortable"></ul>
        </div>
        <div  class = "spring_semester">
          <button type="button" id = "reset-two" class="reset-btn">Reset</button>
          <h4>Semester 2</h4>
          <h6 class = "credits_title"><em>Credits: <span class = "credits" id = "semester-two-credits">0</span></em></h6>
          <ul id = "semester2" class = "connectedSortable"></ul>
        </div>
    </div>

    <div class = "year-two year">
        <div class = "year-div">
          <center><h3>Year Two</h3></center>
        </div>
        <div  class = "fall_semester">
          <button type="button" id = "reset-three" class="reset-btn">Reset</button>
          <h4>Semester 3</h4>
          <h6 class = "credits_title"><em>Credits: <span class = "credits" id = "semester-three-credits">0</span></em></h6>
          <ul id = "semester3" class = "connectedSortable"></ul>
        </div>
        <div  class = "spring_semester">
          <button type="button" id = "reset-four" class="reset-btn">Reset</button>
          <h4>Semester 4</h4>
          <h6 class = "credits_title"><em>Credits: <span class = "credits" id = "semester-four-credits">0</span></em></h6>
          <ul id = "semester4" class = "connectedSortable"></ul>
        </div>
    </div>

    <div class = "year-three year">
        <div class = "year-div">
          <center><h3>Year Three</h3></center>
        </div>
        <div  class = "fall_semester">
          <button type="button" id = "reset-five" class="reset-btn">Reset</button>
          <h4>Semester 5</h4>
          <h6 class = "credits_title"><em>Credits: <span class = "credits" id = "semester-five-credits">0</span></em></h6>
          <ul id = "semester5" class = "connectedSortable"></ul>
        </div>
        <div class = "spring_semester">
          <button type="button" id = "reset-six" class="reset-btn">Reset</button>
          <h4>Semester 6</h4>
          <h6 class = "credits_title"><em>Credits: <span class = "credits" id = "semester-six-credits">0</span></em></h6>
          <ul id = "semester6" class = "connectedSortable"></ul>
        </div>
    </div>

    <div class = "year-four year">
      <div class = "year-div">
        <center><h3>Year Four</h3></center>
      </div>
      <div class = "fall_semester">
        <button type="button" id = "reset-seven" class="reset-btn">Reset</button>
        <h4>Semester 7</h4>
        <h6 class = "credits_title"><em>Credits: <span class = "credits" id = "semester-seven-credits">0</span></em></h6>
        <ul id = "semester7" class = "connectedSortable"></ul>
      </div>
      <div class = "spring_semester">
        <button type="button" id = "reset-eight" class="reset-btn">Reset</button>
        <h4>Total Semester 8</h4>
        <h6 class = "credits_title"><em>Credits: <span class = "credits" id = "semester-eight-credits">0</span></em></h6>
        <ul id = "semester8" class = "connectedSortable"></ul>
      </div>
    </div>
   </div>
  </div>
</div>


</body>

</html>
