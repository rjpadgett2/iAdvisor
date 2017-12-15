<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Droppable - Revert draggable position</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
    <link href="jquery-ui-1.12.1.custom/jquery-ui.css" rel="stylesheet">
    <link href="jquery-ui-1.12.1.custom/jquery-ui.theme.css" rel="stylesheet">
    <script src="jquery-ui-1.12.1.custom/jquery-ui.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <style>
  div:not(:last-child) {
  margin-right: 500px;
  overflow:auto;
  }
  #sortable1, #sortable2 {
   border: 1px solid #eee;
   width: 300px;
   min-height: 20px;
   list-style-type: none;
   margin: 0;
   padding: 5px 0 0 0;
   float: left;
   margin-right: 10px;
 }
 #sortable1 li, #sortable2 li {
   margin: 0 5px 5px 5px;
   padding: 10px;
   font-size: 1.2em;
   width: 300px;
 }
  .draggable {
    width: 100px;
    height: 100px;
    padding: 0.5em;
    float: left;
    margin: 10px 10px 10px 0;
  }
  #droppable {
    width: 150px;
    min-height: 150px;
    padding: 0.5em;
    /*float: left; */
    margin: 10px;
  }
  #list li {
    border: 1px solid blue;
    list-style: none;
  }
  ul {
    padding: 0;
  }
  #message {
    margin-top: 185px;
    width: 500px;

  }
  </style>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>

  $( function() {
   $( "#sortable1, #sortable2" ).sortable({
     connectWith: ".connectedSortable"
   }).disableSelection();
 } );

</script>

</head>
<?php
$server = "127.0.0.1";
$username = "root";
$password = "nilemonitor354";
$db = "iAdvisor";
$port = "3306";

$year = $_POST['year'];
$classesTaken = $_POST['classes'];

$connection = mysqli_connect($server, $username, $password, $db, $port);
// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
$query = "SELECT
    abbreviation, class_num, class_name, core
FROM
    class c
        JOIN
    school s ON c.school_id = s.school_id
ORDER BY class_num";
//This array will hold the names of each class & if they are cores
$classList = array();
$cores = array();
$result = mysqli_query($connection, $query);
//Queries the database to get the individual parts of the class names and combine them
if ($result->num_rows > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($classList, $row["abbreviation"] . (string)$row["class_num"] . ": " . $row["class_name"]);
		array_push($cores, $row["core"]);
    }

} else {
    echo "0 results";
}
//Function for troubleshooting
function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);
    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}
//Function to see if a class is a core class or not
function isCore($className, $classList, $cores){
	if (in_array($className, $classList)){
		$index = array_search($className, $classList);
		if ($cores[$index] > 0){
			return true;
		}
	}
	else {
		return false;
	}
}
//This array will hold the classes to take. Starts by having a list of every class
$classesNeeded = $classList;
//This loop will eliminate the classes from the list if the user has already completed them
for ($i=0; $i<sizeof($classesNeeded); $i++){
	if (isCore($classesNeeded[$i], $classList, $cores)){
		if (in_array($classesNeeded[$i], $classesTaken)){
			unset($classesNeeded[$i]);
			$classesNeeded = array_values($classesNeeded);
			$i--;
		}
	}

}
echo "You are a: ".$year."<br><br>";
echo "You have taken:<br>";
foreach ($classesTaken as $classes){
	echo $classes. "<br>";
}
echo "<br>You still need to take:<br>";
foreach ($classesNeeded as $classes){
	echo $classes. "<br>";
}
?>
<body>

  <center><H1>Your 4 year Plan<H1></center>
  <div id = "test">
    <ul id="jsonData">
        <li></li>
    </ul>

  </div>

  <div id = "yearOne">
    <center><H3>Year One<H3></center>

      <ul id="sortable1" class="connectedSortable">
        <li class="ui-state-default">PSYC100</li>
        <li class="ui-state-default">INST126</li>
        <li class="ui-state-default">STAT100</li>
        <li class="ui-state-default">MATH115</li>
        <li class="ui-state-default">INST126</li>
      </ul>

      <ul id="sortable2" class="connectedSortable">
        <li class="ui-state-highlight">INST201</li>
        <li class="ui-state-highlight">INST311</li>
        <li class="ui-state-highlight">INST314</li>
        <li class="ui-state-highlight">INST326</li>
      </ul>`
  </div>

  <div id = "yearTwo">
    <center><H3>Year Two<H3></center>
    <ul id="sortable2" class="connectedSortable">
      <li class="ui-state-default">INST327</li>
      <li class="ui-state-default">INST335</li>
      <li class="ui-state-default">INST346</li>
      <li class="ui-state-default">INST352</li>
    </ul>

    <ul id="sortable2" class="connectedSortable">
      <li class="ui-state-highlight">INST354</li>
      <li class="ui-state-highlight">INST362</li>
      <li class="ui-state-highlight">INST377</li>
    </ul>`
  </div>

  <div id = "yearThree">
    <center><H3>Year Three<H3></center>
    <ul id="sortable2" class="connectedSortable">
      <li class="ui-state-default">INST408B</li>
      <li class="ui-state-default">INST414</li>
    </ul>

    <ul id="sortable2" class="connectedSortable">
      <li class="ui-state-highlight">INST447</li>
      <li class="ui-state-highlight">INST462</li>

    </ul>`

  </div>

  <div id = "yearFour">
    <center><H3>Year Four<H3></center>
    <ul id="sortable2" class="connectedSortable">
      <li class="ui-state-default">INST466</li>

    </ul>

    <ul id="sortable2" class="connectedSortable">
      <li class="ui-state-highlight">INST490</li>

    </ul>`
  </div>
<!--
<div>
  <div id="droppable" class="ui-widget-header">
    <p>Drop data here. How much alcohol do you drink per week?</p>
    <ul id="list">
    </ul>
  </div>

  <div class="ui-widget-content draggable">
    <p>19 years old, 2 times</p>
  </div>
  <div class="ui-widget-content draggable">
    <p>23 years old, 5 times</p>
  </div>
  <div class="ui-widget-content draggable">
    <p>30 years old, 4 times</p>
  </div>
  <div class="ui-widget-content draggable">
    <p>15 years old, 0 times</p>
  </div>
</div>

<div id="message" class="ui-widget-content ui-state-highlight">
</div>
-->


</body>
</html>
