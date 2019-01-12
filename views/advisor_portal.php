<?php
include_once("../partials/header.php");
?>
  <link rel="stylesheet" href="../styles/agenda_maker_style.css?version=0.6">
    <script src="../scripts/advisor_portal.js?version=0.4"> </script>
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
        <a class="nav-buttons" href="../routes/login/registration.php?action=advisor_logout"><i class="fa fa-fw fa-sign-in"></i>Logout</a>
     </div>
    <?php }
   else{
     ?>
     <a id = "advisor-btn" class="nav-buttons">Advisor Login</a>
     <a id = "advisor-register-btn" class="nav-buttons"><i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>Advisor Register</a>
    <?php } ?>
 </div>
 <div>
   <h1><center>Welcome Back <?php echo $_SESSION['username']; ?></center></h1>
 </div>

</body>
</html>
