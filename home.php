<?php
// Initialize the session
session_start();


// If session variable is not set it will redirect to login page
if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
  header("location: index.php");
  exit;
}

$currentUser = $_SESSION['email'];

$current_user_data_query = "SELECT last_name, first_name FROM Studnets WHERE email = '$currentUser'";
$current_user_data_query_results = mysqli_query($connection, $current_user_data_query);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

    <link rel="stylesheet" href="home.css">

    <meta charset = "utf-8">
    <meta name = "viewport" content="width=device-width, initial-scale = 1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.6/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

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
           <a href="#"><span class="glyphicon glyphicon-home"></span>Home</a>
         </li>
         <li>
           <a href="agenda_maker.php"><span class="glyphicon glyphicon-file"></span>Create Class Schedule</a>
         </li>
       </ul>

       <ul class="nav navbar-nav navbar-right">
        <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li> -->
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
      </ul>
     </div>
    </nav>

    <div class="page-header">
        <center><h1>Hi, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>. Welcome to our site.</h1></center>
        <center><img src = "iadvisor.png" height = "300"></center>
    </div>
</body>
</html>
