<!DOCTYPE html>
<html>
<head>
</head>
<body>

<?php

$server = "35.185.100.59 ";
$username = "root";
$password = "root";
$db = "iadvisor";
//$port = "3306";

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

//This array will hold the names of each class
$classList = array();

$result = mysqli_query($connection, $query);
//Queries the database to get the individual parts of the class names and combine them
if ($result->num_rows > 0) {
    while($row = mysqli_fetch_assoc($result)) {
		array_push($classList, $row["abbreviation"] . (string)$row["class_num"] . ": " . $row["class_name"]);
    }

} else {
    echo "0 results";
}

$years = array("Freshman", "Sophmore", "Junior", "Senior");

//Start of the form
echo "<form action='form_check.php' name='myForm' method='post'>";


echo "<div>What year are you?</div>";
//Adds radio buttons for year
$i = 0;
foreach ($years as $value) {
	echo  "<input type='radio' name = 'year' value ='". $value ."''> " . $value . "<br>";
	$i++;
}

echo "<br><div>What classes have you taken already?</div>";
//Adds the list of classes as checkboxes
$i = 0;
foreach ($classList as $value) {
	echo  "<input type='checkbox' name='classes[]' value ='". $value ."'> " . $value . "<br>";
	$i++;
}
echo "<br><input type='submit' value='submit'>";
echo "</form>";
?>

</body>
</html>
