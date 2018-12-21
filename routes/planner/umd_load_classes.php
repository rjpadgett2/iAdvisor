<?php

//include connection file
require_once '../../connect/config.php';
// require_once '/connect/config.php"';
$db = new dbObj();
$connString =  $db->getConnstring();

$params = $_REQUEST;
$action = $params['action'] !='' ? $params['action'] : '';

$dept = "";
$class_name = "";
$credits = "";



$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.umd.io/v0/courses",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

$response = json_decode($response, true); //because of true, it's in an array
// echo 'Online: '. $response;
print_r($response);




?>
