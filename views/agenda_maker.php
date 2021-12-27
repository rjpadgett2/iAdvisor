<?php
include_once("../partials/header.php");
?>
  <link rel="stylesheet" href="../styles/agenda_maker_style.css?version=0.2">
    <script src="../scripts/agenda_maker.js?version=0.8"> </script>
      <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
</head>

<body>
  <div class="topnav">
    <a class="active" href="../index.php">
      <img src="../img/iAdvisorLogo.png" alt="Logo" style="width:100px;">
    </a>
    <?php
    if(isset($_SESSION['email'])){?>
      <div class="topnav-right">
        <button class="nav-buttons" >
          <a href = "../about.html"><i class="fa fa-fw fa-info-circle" aria-hidden="true"></i>About</a>
        </button>
        <button class="nav-buttons" >
          <a href = "https://uachieve.umd.edu/"><i class="fa fa-fw fa-star-o" aria-hidden="true"></i>UAchieve</a>
        </button>
        <button class="nav-buttons" >
          <a href = ""><i class="fa fa-fw fa-question" aria-hidden="true"></i>Help</a>
        </button>
        <button class="nav-buttons" >
          <a href="../routes/login/registration.php?action=student_logout"><i class="fa fa-fw fa-sign-in"></i>Logout</a>
        </button>
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
  <div class="modal" id="infoModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <center><h3 class="modal-title"></h3></center>
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
<!--******************** Message Modal ************************-->
<div class="modal" id="msgModal" role="dialog">
  <div class="modal-content">
    <div class="modal-header">
      <center><h3 class="modal-title">Do You Want To Send a Message To Your Advisor?</h3></center>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
      <div id = "msgDiv">
        <form id="msgForm">
          <textarea rows="12" cols="50" name="comment" id = "comment" form="msgForm"></textarea>
          <br>
          <input type="submit" onclick = "addMsg(document.getElementById('comment').value)" >
        </form>
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
    <button type="button" onclick="addDataToDB()" id = "save-btn" class="funct-btns">Save</button>
    <button type="button" onclick="leaveNote()" id = "note-btn" class="funct-btns">Leave Note</button>
    <button type="button" onclick="resetDB()" id = "reset-all-btn" class="funct-btns">Reset All</button>
    <button type="button" onclick="submitClasses()" id = "submit-btn" class="funct-btns">Submit</button>
  </div>
</div>
<!-- Student 4 year Plan -->
<div class = "content">
  <div id = "search-div" class = "">
    <button id= "filter_button" onclick="showFilterBox(this)"><span class="glyphicon glyphicon-filter"></span> Filter</button>
    <div id = "filter_box">
      <form action="" method="post">
        <label>Name:&nbsp;</label><input type="checkbox" name="by_name" />
        <label>Sex:&nbsp;</label><input type="checkbox" name="by_sex" />
        <label>Blood Group:&nbsp;</label><input type="checkbox" name="by_group" />
        <label>Level:&nbsp;</label><input type="checkbox" name="by_level" />
        <br>
        <input class="button" type="submit" name="submit" value="Search" />
      </form>
    </div>
    <datalist id="class_results" ></datalist>
    <div id="search_result">
      <ul id = "search_result_list" class = "connectedSortable semester_ul"></ul>
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
            <button type="button" id = "reset-one" onclick="resetSemester(this)" class="reset-btn">Reset</button>
            <h3>Fall</h3>
            <h6 class = "credits_title"><em>Credits: <span class = "credits" id = "semester-one-credits">0</span></em></h6>
            <ul id="semester1" class="connectedSortable semester_ul"></ul>
        </div>
        <div  class = "spring_semester">
          <button type="button" id = "reset-two" onclick="resetSemester(this)" class="reset-btn">Reset</button>
          <h3>Spring</h3>
          <h6 class = "credits_title"><em>Credits: <span class = "credits" id = "semester-two-credits">0</span></em></h6>
          <ul id = "semester2" class = "connectedSortable semester_ul"></ul>
        </div>
        <div class = "winter_semester">
            <button type="button" id = "reset-one-a" onclick="resetSemester(this)" class="reset-btn">Reset</button>
            <h3>Winter</h3>
            <h6 class = "credits_title"><em>Credits: <span class = "credits">0</span></em></h6>
            <ul id="winter_semester1" class="connectedSortable semester_ul"></ul>
        </div>
        <div  class = "summer_semester">
          <button type="button" id = "reset-two-b" onclick="resetSemester(this)" class="reset-btn">Reset</button>
          <h3>Summer</h3>
          <h6 class = "credits_title"><em>Credits: <span class = "credits">0</span></em></h6>
          <ul id = "summer_semester2" class = "connectedSortable semester_ul"></ul>
        </div>
    </div>

    <div class = "year-two year">
        <div class = "year-div">
          <center><h3>Year Two</h3></center>
        </div>
        <div  class = "fall_semester">
          <button type="button" id = "reset-three" onclick="resetSemester(this)" class="reset-btn">Reset</button>
          <h3>Fall</h3>
          <h6 class = "credits_title"><em>Credits: <span class = "credits" id = "semester-three-credits">0</span></em></h6>
          <ul id = "semester3" class = "connectedSortable semester_ul"></ul>
        </div>
        <div  class = "spring_semester">
          <button type="button" id = "reset-four" onclick="resetSemester(this)" class="reset-btn">Reset</button>
          <h3>Spring</h3>
          <h6 class = "credits_title"><em>Credits: <span class = "credits" id = "semester-four-credits">0</span></em></h6>
          <ul id = "semester4" class = "connectedSortable semester_ul"></ul>
        </div>
        <div class = "winter_semester">
            <button type="button" id = "reset-one-a" onclick="resetSemester(this)" class="reset-btn">Reset</button>
            <h3>Winter</h3>
            <h6 class = "credits_title"><em>Credits: <span class = "credits">0</span></em></h6>
            <ul id="winter_semester3" class="connectedSortable semester_ul"></ul>
        </div>
        <div class = "summer_semester">
          <button type="button" id = "reset-two-b" onclick="resetSemester(this)" class="reset-btn">Reset</button>
          <h3>Summer</h3>
          <h6 class = "credits_title"><em>Credits: <span class = "credits">0</span></em></h6>
          <ul id = "summer_semester4" class = "connectedSortable semester_ul"></ul>
        </div>
    </div>

    <div class = "year-three year">
        <div class = "year-div">
          <center><h3>Year Three</h3></center>
        </div>
        <div  class = "fall_semester">
          <button type="button" id = "reset-five" onclick="resetSemester(this)" class="reset-btn">Reset</button>
          <h3>Fall</h3>
          <h6 class = "credits_title"><em>Credits: <span class = "credits" id = "semester-five-credits">0</span></em></h6>
          <ul id = "semester5" class = "connectedSortable semester_ul"></ul>
        </div>
        <div class = "spring_semester">
          <button type="button" id = "reset-six" onclick="resetSemester(this)" class="reset-btn">Reset</button>
          <h3>Spring</h3>
          <h6 class = "credits_title"><em>Credits: <span class = "credits" id = "semester-six-credits">0</span></em></h6>
          <ul id = "semester6" class = "connectedSortable semester_ul"></ul>
        </div>
        <div class = "winter_semester">
            <button type="button" id = "reset-one-a" onclick="resetSemester(this)" class="reset-btn">Reset</button>
            <h3>Winter</h3>
            <h6 class = "credits_title"><em>Credits: <span class = "credits">0</span></em></h6>
            <ul id="winter_semester5" class="connectedSortable semester_ul"></ul>
        </div>
        <div  class = "summer_semester">
          <button type="button" id = "reset-two-b" onclick="resetSemester(this)" class="reset-btn">Reset</button>
          <h3>Summer</h3>
          <h6 class = "credits_title"><em>Credits: <span class = "credits">0</span></em></h6>
          <ul id = "summer_semester6" class = "connectedSortable semester_ul"></ul>
        </div>
    </div>

    <div class = "year-four year">
      <div class = "year-div">
        <center><h3>Year Four</h3></center>
      </div>
      <div class = "fall_semester">
        <button type="button" id = "reset-seven" onclick="resetSemester(this)" class="reset-btn">Reset</button>
        <h3>Fall</h3>
        <h6 class = "credits_title"><em>Credits: <span class = "credits" id = "semester-seven-credits">0</span></em></h6>
        <ul id = "semester7" class = "connectedSortable semester_ul"></ul>
      </div>
      <div class = "spring_semester">
        <button type="button" id = "reset-eight" onclick="resetSemester(this)" class="reset-btn">Reset</button>
        <h3>Spring</h3>
        <h6 class = "credits_title"><em>Credits: <span class = "credits" id = "semester-eight-credits">0</span></em></h6>
        <ul id = "semester8" class = "connectedSortable semester_ul"></ul>
      </div>
      <div class = "winter_semester">
          <button type="button" id = "reset-one-a" onclick="resetSemester(this)" class="reset-btn">Reset</button>
          <h3>Winter</h3>
          <h6 class = "credits_title"><em>Credits: <span class = "credits">0</span></em></h6>
          <ul id="winter_semester7" class="connectedSortable semester_ul"></ul>
      </div>
      <div  class = "summer_semester">
        <button type="button" id = "reset-two-b" onclick="resetSemester(this)" class="reset-btn">Reset</button>
        <h3>Summer</h3>
        <h6 class = "credits_title"><em>Credits: <span class = "credits">0</span></em></h6>
        <ul id = "summer_semester8" class = "connectedSortable semester_ul"></ul>
      </div>
    </div>
   </div>
  </div>
</div>


</body>

</html>
