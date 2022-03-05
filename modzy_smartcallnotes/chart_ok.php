
<?php

error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);


include('data6rst.php');

$jobid = $_GET['st'];

$data[] = array('Sentiments', 'Scores');

$result = $db->prepare('SELECT * FROM sentiments where jobid=:jobid');
$result->execute(array(':jobid' =>$jobid));
$nosofrows = $result->rowCount();
while($row = $result->fetch()){
$id= $row['id'];


//foreach($json['data'] as $v1){

$score = $row['score'];
$score1  = $score *100;
$sentiments = $row['sentiments'];


//$payers= 'Names of Payers';
$senti= "$sentiments. Scores: ($score)";
//$data[] = array($payers,(int)$type,(int)$percent,(int)$seconds);
$data[] = array($senti,(int)$score1);
}



echo json_encode($data);

