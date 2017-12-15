<!DOCTYPE html>
<html>
<head>
</head>
<body>

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
	echo $classes."<br>";
}
echo "<br>You still need to take:<br>";
foreach ($classesNeeded as $classes){
	echo $classes."<br>";
}
?>

</body>
</html>
