<?Php
///////// Database Details change here  ////
$dbhost_name = "127.0.0.1";
$database = "iAdvisor";
$username = "root";
$password = "nilemonitor354";

//////// Do not Edit below /////////
try {
$dbo = new PDO('mysql:host=127.0.0.1;dbname='.$database, $username, $password);
} catch (PDOException $e) {
print "Error!: " . $e->getMessage() . "<br/>";
die();
}

?>
