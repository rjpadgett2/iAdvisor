<?php
require "../../connect/search_config.php"; // Database Connection
//$search_text="plus2net demo ";
@$search_text=$_GET['txt'];
//echo $search_text;
/////////// Start of preparation for Paging //////
@$end_record=$_GET['endrecord'];//
//$end_record=20;
if(strlen($end_record) > 0 AND (!is_numeric($end_record))){
  echo "Data Error 1";
  exit;
}
$limit=10; // Number of records per page
if($end_record < $limit) {
  $end_record = 0;
}
switch(@$_GET['direction']) {
  case "fw":
  $start_record = $end_record ;
  break;
  case "bk":
  $start_record = $end_record - 2*$limit;
  break;
  default:
  //echo "Data Error";
  //exit;
  $start_record=0;
  break;
}
if($start_record < 0){
  $start_record=0;
}
$end_record =$start_record+$limit;
////////// End of preparation for paging /////
$search_text=trim($search_text);
$message = '';

$query='';
$query2='';
//$query2="select title,file_id,file,des from plus2_file_php where title like '%search_text%'" ;
//$row2=$dbo->prepare($query2);
//$row2->execute();
//$result2=$row2->fetchAll(PDO::FETCH_ASSOC);

$kt=preg_split("/[\s,]+/",$search_text);//Breaking the string to array of words
// Now let us generate the sql
foreach ($kt as $key => $val){
  if(!ctype_alnum($val)){
    $message .= " Data Error 2 ";
    //echo "Data Error";
    $main = array('value'=>array("message"=>"$message"));
    exit(json_encode($main));
  }

  if($val<>" " and strlen($val) > 0){
    $query .= " class_name like '%$val%' or ";
    $query2 .= " class_name like '%$val%' and ";
  }
}// end of while

$query=substr($query,0,(strLen($query)-3));
$query2=substr($query2,0,(strLen($query2)-4));
// this will remove the last or from the string.
$q="select count(class_id) from Course where ". $query;
$q2="select count(class_id) from Course where ". $query2;
$query= 'select class_id , class_abbreviation, class_name, credits from Course where '. $query . " limit $start_record,$limit " ;
$query2= 'select class_id , class_abbreviation, class_name, credits from Course where '. $query2 . " limit $start_record,$limit " ;
///////////////End of adding key word search query //////////
//echo $query;
$message .=$query;
$records_found = $dbo->query($q)->fetchColumn(); // Number of records found
$records_found2 = $dbo->query($q2)->fetchColumn(); // Number of records found

$records_found_total=$records_found + $records_found2;
$row=$dbo->prepare($query);
$row->execute();
$result=$row->fetchAll(PDO::FETCH_ASSOC);

$row2=$dbo->prepare($query2);
$row2->execute();
$result2=$row2->fetchAll(PDO::FETCH_ASSOC);


$result=array_merge($result2,$result);
//$nume=count($result);
if(($end_record) < $records_found_total ){
  $end="yes";
}
else{
  $end="no";
}

if(($end_record) > $limit ){
  $startrecord="yes";
}
else{
  $startrecord="no";
}


$main = array('data'=>$result,'value'=>array("no_records"=>"$records_found","no_records2"=>"$records_found2","message"=>"$message","status1"=>"T","endrecord"=>"$end_record","limit"=>"$limit","end"=>"$end","startrecord"=>"$startrecord" ));
echo json_encode($main);
?>
