<?php

$server = "localhost";
$username = "root";
$password = "root";
$db = "iAdvisor";

// Create connection
$conn = mysqli_connect($server, $username, $password, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully<br><br>";

$newUserResults = $_POST["checkbox"];
$firstName = $_POST["firstname"];
$lastName = $_POST["lastname"];

/*
if (isset($newUserResults)){
	// $animal = stripslashes($animal);
	$animal = str_replace(' ', '', $animal);
	echo "Received <strong>" . $animal . "</strong> from the form.";
} else {
	echo "Nothing was received...";
}
*/

// A function for general queries.
function query_to_db($conn, $sql){
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "Your query was successful";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

}


echo "Submitted Data: <br>";
echo "Age? " . $age . "<br>";

if ($age > 21){
	echo "The user's weekly alcohol consumption frequecy: " . $freq . " times/week. ";
}

$answer1 = $age > 21 ? "yes" : "no";


// // You need to save the data into the database.


mysqli_close($conn);










?>
