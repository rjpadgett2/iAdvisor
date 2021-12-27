<?php
include_once("partials/header.php");
require '/path/to/sdk/vendor/autoload.php';

use Doctrine\Common\Cache\FilesystemCache;
use Guzzle\Cache\DoctrineCacheAdapter;

// Create a cache adapter that stores data on the filesystem
$cacheAdapter = new DoctrineCacheAdapter(new FilesystemCache('/tmp/cache'));

// Provide a credentials.cache to cache credentials to the file system
$s3Client = Aws\S3\S3Client::factory(array(
    'credentials.cache' => $cacheAdapter
?>

    <link rel="stylesheet" href="../styles/index.css?version=1.5">
    <script src="/scripts/index.js?version=3.7"> </script>
</head>
  <body>

  <div class="topnav">
    <a class="nav-buttons active" href="../index.php">
      <img src="../img/iAdvisorLogo.png" alt="Logo" style="width:100px;">
    </a>
    <?php
    if(isset($_SESSION['email'])){?>
      <a class="nav-buttons" href="/views/agenda_maker.php">4 Year Plan</a>
      <div class="topnav-right">
        <a class="nav-buttons" href = "/about.html"><i class="fa fa-fw fa-info-circle" aria-hidden="true"></i>About</a>
        <a class="nav-buttons" href = "https://uachieve.umd.edu/"><i class="fa fa-fw fa-star-o" aria-hidden="true"></i>UAchieve</a>
        <a class="nav-buttons" href = ""><i class="fa fa-fw fa-question" aria-hidden="true"></i>Help</a>
        <a class="nav-buttons" href="/routes/login/registration.php?action=student_logout"><i class="fa fa-fw fa-sign-in"></i>Logout</a>
     </div>
    <?php }
   else{
     ?>
      <!-- <button id = "register-btn" class="nav-buttons">Register</button>
      <button id = "student-btn" class="nav-buttons"><i class="fa fa-fw fa-user"></i>Login</button> -->
      <a id = "advisor-btn" class="nav-buttons">Advisor Login</a>
      <a id = "advisor-register-btn" class="nav-buttons"><i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>Advisor Register</a>
      <div class="topnav-right">
        <a id = "student-register-btn" class="nav-buttons"><i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>Student Register</a>
        <a id = "student-btn" class="nav-buttons"><i class="fa fa-fw fa-sign-in"></i>Login</a>
      </div>
    <?php } ?>
 </div>

<div id="student_login" class="modal">
  <div class="row box" id="login-box">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-login">
    <div class="imgcontainer">
      <span id = "std-close" onclick="document.getElementById('student_login').style.display='none'" class="close" title="Close Modal">&times;</span>
    </div>

		<div class="panel-body">
			<div class="row">
				<div class="col-lg-12">
				  <div class="alert alert-danger" role="alert" id="error" style="display: none;">...</div>

				  <form id="student_login_form" name="student_login_form" role="form" style="display: block;" method="post">
					  <div class="form-group">
						<input type="email" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value=""  required>
					  </div>
					  <div class="form-group">
						<input type="password" name="password" id="login_password" tabindex="2" class="form-control" placeholder="Password" required>
					  </div>
					  <div class="col-xs-12 form-group pull-right">
							<button type="submit" name="student_login-submit" id="student_login-submit" tabindex="4" class="form-control btn btn-primary">
							 <span class="spinner"><i class="icon-spin icon-refresh" id="spinner"></i></span> Log In
							</button>
					  </div>
				  </form>
				</div>
			</div>
		</div>
		</div>
	</div>
</div>
</div>

<div id = "student_registration" class = "modal">
  <div class="col-md-8 col-md-offset-2">
  <div class="panel panel-login">

    <div class="alert alert-info">
     <h2>Student Registration</h2>
    </div>
    <div class="imgcontainer">
      <span id = "std-reg-close" onclick="document.getElementById('student_registration').style.display='none'" class="close" title="Close Modal">&times;</span>
    </div>
  <div class="panel-body">
    <div class="row">
      <div class="col-lg-12">
        <div class="alert alert-danger" role="alert" id="error" style="display: none;">...</div>
          <div class="alert alert-success" role="alert" id="success" style="display: none;">...</div>

        <form id="student_register-form" method="post" role="form">
          <div class="messages"></div>
        <div class="controls">
        <div class="row">
        <div class="col-md-6">
        <div class="form-group">
        <label for="form_name">Firstname *</label>
        <input id="form_name" type="text" name="f_name" class="form-control" placeholder="Please enter your firstname *" required="required" data-error="Firstname is required.">
        <div class="help-block with-errors"></div>
        </div>
        </div>
        <div class="col-md-6">
        <div class="form-group">
        <label for="form_lastname">Lastname *</label>
        <input id="form_lastname" type="text" name="l_name" class="form-control" placeholder="Please enter your lastname *" required="required" data-error="Lastname is required.">
        <div class="help-block with-errors"></div>
        </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-12">
        <div class="form-group">
        <label for="form_email">Email *</label>
        <input id="form_email" type="email" name="email" class="form-control" placeholder="Please enter your email *" required="required" data-error="Valid email is required.">
        <div class="help-block with-errors"></div>
        </div>
        </div>
        </div>
        <div class="row">

        <div class="col-md-6">
        <div class="form-group">
        <label for="form_pass">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Please enter your password">
        <div class="help-block with-errors"></div>
        </div>
        </div>
        <div class="col-md-6">
        <div class="form-group">
        <label for="form_re_pass">Re-Enter Password</label>
        <input type="password" name="re_password" id="re_password"  class="form-control" placeholder="Please enter again password">
        <div class="help-block with-errors"></div>
        </div>
        </div>
        <div class="col-md-12">
        <div class="form-group">
        <div class="g-recaptcha" data-sitekey="your site key captcha"></div>
        </div>
        </div>
        <div class="col-md-12">
        <button type="submit" class="btn btn-success btn-send" id="register_button" name="register_button" value="Register">Register</button>
        </div>
        </div>
        </div>
        </form>
      </div>
    </div>
  </div>
  </div>
</div>
</div>
</div>



<div id="advisor_login" class="modal">
  <div class="row box" id="adv-login-box">
  <div class="col-md-6 col-md-offset-3">
    <div class="panel panel-login">
    <div class="imgcontainer">
      <span id = "adv-close" onclick="document.getElementById('advisor_login').style.display='none'" class="close" title="Close Modal">&times;</span>
    </div>

    <div class="panel-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="alert alert-danger" role="alert" id="error" style="display: none;">...</div>

          <form id="advisor_login_form" name="advisor_login_form" role="form" style="display: block;" method="post">
            <div class="form-group">
            <input type="email" name="username" id="adv-username" tabindex="1" class="form-control" placeholder="Username" value=""  required>
            </div>
            <div class="form-group">
            <input type="password" name="password" id="adv_login_password" tabindex="2" class="form-control" placeholder="Password" required>
            </div>
            <div class="col-xs-12 form-group pull-right">
              <button type="submit" name="advisor_login-submit" id="advisor_login-submit" tabindex="4" class="form-control btn btn-primary">
               <span class="spinner"><i class="icon-spin icon-refresh" id="spinner"></i></span> Log In
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    </div>
  </div>
</div>
</div>


<div id = "advisor_registration" class = "modal">
  <div class="col-md-8 col-md-offset-2">
  <div class="panel panel-login">

    <div class="alert alert-info">
     <h2>Advisor Registration</h2>
    </div>
    <div class="imgcontainer">
      <span id = "adv-reg-close" onclick="document.getElementById('advisor_registration').style.display='none'" class="close" title="Close Modal">&times;</span>
    </div>
  <div class="panel-body">
    <div class="row">
      <div class="col-lg-12">
        <div class="alert alert-danger" role="alert" id="error" style="display: none;">...</div>
          <div class="alert alert-success" role="alert" id="success" style="display: none;">...</div>

        <form id="advisor_register-form" method="post" role="form">
          <div class="messages"></div>
        <div class="controls">
        <div class="row">
        <div class="col-md-6">
        <div class="form-group">
        <label for="form_name">Firstname *</label>
        <input id="adv-form_name" type="text" name="f_name" class="form-control" placeholder="Please enter your firstname *" required="required" data-error="Firstname is required.">
        <div class="help-block with-errors"></div>
        </div>
        </div>
        <div class="col-md-6">
        <div class="form-group">
        <label for="form_lastname">Lastname *</label>
        <input id="adv-form_lastname" type="text" name="l_name" class="form-control" placeholder="Please enter your lastname *" required="required" data-error="Lastname is required.">
        <div class="help-block with-errors"></div>
        </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-12">
        <div class="form-group">
        <label for="form_email">Email *</label>
        <input id="adv-form_email" type="email" name="email" class="form-control" placeholder="Please enter your email *" required="required" data-error="Valid email is required.">
        <div class="help-block with-errors"></div>
        </div>
        </div>
        </div>
        <div class="row">

        <div class="col-md-6">
        <div class="form-group">
        <label for="form_pass">Password</label>
        <input type="password" name="password" id="adv-password" class="form-control" placeholder="Please enter your password">
        <div class="help-block with-errors"></div>
        </div>
        </div>
        <div class="col-md-6">
        <div class="form-group">
        <label for="form_re_pass">Re-Enter Password</label>
        <input type="password" name="re_password" id="adv_re_password"  class="form-control" placeholder="Please enter again password">
        <div class="help-block with-errors"></div>
        </div>
        </div>
        <div class="col-md-12">
        <div class="form-group">
        <div class="g-recaptcha" data-sitekey="your site key captcha"></div>
        </div>
        </div>
        <div class="col-md-12">
        <button type="submit" class="btn btn-success btn-send" id="adv_register_button" name="register_button" value="Register">Register</button>
        </div>
        </div>
        </div>
        </form>
      </div>
    </div>
  </div>
  </div>
</div>
</div>
</div>



    <!-- Sign-in button -->
   <?php
    if(isset($_SESSION['email'])){?>
    <div id="studentBackgroundImage">
      <center><H1 class = "titles">Welcome Back <?php echo $_SESSION['username']; ?><H1></center>
        <div  class="container">
        <center>
          <span><img src = "img/iadvisor.png" height = "400"></span>
        </center>
        </div>
      </div>
   <?php }
   else{
    ?>
    <div id="backgroundImage">
      <center><H1 class = "titles">Welcome to iAdvisor<H1></center>
        <div  class="container">
        <center>
          <span><img src = "img/iadvisor.png" height = "400"></span>
        </center>
          <center><H3 class = "titles">The stress free online advising service</H3></center>
          <center><h6>For Testing Purposes Username: jdoe@umd.edu Password: password.</h6></center>
        </div>
      </div>
    <?php } ?>



<?php
include_once("partials/footers.php");
?>
