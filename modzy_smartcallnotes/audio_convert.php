<?php
//error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 

ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);


?>

       
<?php

if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {



$uid = strip_tags($_POST['uid']);
 $jobid = strip_tags($_POST['jobid']);
$postid = strip_tags($_POST['postid']);


include('settings.php');


// Ensure that Site url is set from Application settings Menu
if($site_url ==''){
echo "<br><div id='alertdata_uploadfiles_o' style='background:red;color:white;padding:10px;border:none;'>
Please Ensure to set Site Project Url from Application settings Menu</div><br>";
exit();	
}


// Ensure that rev.ai api key is set from Application settings Menu
if($revai_apikey ==''){
echo "<br><div id='alertdata_uploadfiles_o' style='background:red;color:white;padding:10px;border:none;'>
Please Ensure to set Rev.AI Apikey from Application settings Menu </div><br>";
exit();	
}


// Ensure that modzy ai api key is set from Application settings Menu
if($modzy_apikey ==''){
echo "<br><div id='alertdata_uploadfiles_o' style='background:red;color:white;padding:10px;border:none;'>
Please Ensure to set Modzy ai Apikey from Application settings Menu </div><br>";
exit();	
}




if ($postid == ''){
echo "<div style='background:red;border:none;color:white;padding:10px;' id='alerts_reg'>PostId is empty</div>";
exit();
}


if ($jobid == ''){
echo "<div style='background:red;border:none;color:white;padding:10px;' id='alerts_reg'>JobId is empty</div>";
exit();
}



// Process Transcript via rev.ai


//$jobid_trim = trim($jobid);
$url2 ="https://api.rev.ai/speechtotext/v1/jobs/$jobid/transcript";

$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, $url2);
curl_setopt($ch2, CURLOPT_HTTPHEADER, array("Authorization: Bearer $revai_apikey", 'Accept: text/plain'));  
curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);
$output2 = curl_exec($ch2); 

if($output2==''){

echo "<div id='alertdata_uploadfiles_o' style='background:red;color:white;padding:10px;border:none;'>
Audio/Speech to Text Conversion Failed. Ensure there is Internet Connections</div><br>";
exit();
}

// check if in_progress is contained  in the sentence.

if (preg_match('/\bin_progress\b/', $output2)) {
    //echo 'true';

echo "<div id='alertdata_v'>$output2</div><br>";
echo "<div id='alertdata_v1' style='background:#800000;color:white;padding:10px;border:none;'>
Job is in Progress or in Queue. Please Try Submitting Data in the Next 10 Seconds</div><br>";

echo "

<script>
$(document).ready(function() {

var count=10;

var counter_sec=setInterval(timer_sec, 1000);

function timer_sec()
{
  count=count-1;
  if (count <= 0)
  {
     clearInterval(counter_sec);
alert('Please Try Submiting Data Again Now');
     return;
  }

 //document.getElementById('timer_sec').innerHTML=count + ' seconds'; 

$('#timer_sec').html( count + ' seconds.');
}



});




</script>
<span style='font-size:20px;color:#800000' id='timer_sec' class='alertdata_v2'>x</span>
";


exit();
}




//include database
include('data6rst.php');


$stmt = $db->prepare('UPDATE audio_calls set messages=:messages,status=:status where id = :id');
$stmt->execute(array(':messages' => $output2, ':status' => '1', ':id' => $postid));




if($stmt){
echo "<div id='alertdata_uploadfiles_o' style='background:green;color:white;padding:10px;border:none;'>
Audio Successfully Converted.  Redirecting in 2 seconds .....</div><br><br>";


echo "<script>
window.setTimeout(function() {
    window.location.href = 'dashboard.html';
}, 2000);
</script><br><br>";

}
else {

echo "<div id='alertdata_uploadfiles_o' style='background:red;color:white;padding:10px;border:none;'>
Audio/Speech to Text Conversion Failed.</div><br>";
                }   




}



?>



