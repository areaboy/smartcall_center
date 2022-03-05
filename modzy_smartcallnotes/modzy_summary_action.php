<?php

error_reporting(0);
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);


 $jobid = strip_tags($_POST['jobid']);
 $y_uid = strip_tags($_POST['y_uid']);
 $content = strip_tags($_POST['y_message']);
 $uid = strip_tags($_POST['uid']);


include('settings.php');


// Ensure that Site url is set from Application settings Menu
if($site_url ==''){
echo "<br><div id='alertdata_uploadfiles_o' style='background:red;color:white;padding:10px;border:none;'>
Please Ensure to set Site Project Url from Application settings Menu</div><br>";
exit();	
}


// Ensure that modzy ai api key is set from Application settings Menu
if($modzy_apikey ==''){
echo "<br><div id='alertdata_uploadfiles_o' style='background:red;color:white;padding:10px;border:none;'>
Please Ensure to set Modzy ai Apikey from Application settings Menu </div><br>";
exit();	
}

$token_modzy = $modzy_apikey;





$url1_s ="https://app.modzy.com/api/results/$jobid";

$ch1_s = curl_init();
curl_setopt($ch1_s, CURLOPT_URL, $url1_s);
//curl_setopt($ch1_s, CURLOPT_POST, TRUE);
curl_setopt($ch1_s, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json', "Authorization: ApiKey $token_modzy"));  
//curl_setopt($ch1_s, CURLOPT_POSTFIELDS, $data_param);
curl_setopt($ch1_s, CURLOPT_CUSTOMREQUEST, "GET");
//curl_setopt($ch1_s, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch1_s, CURLOPT_RETURNTRANSFER, TRUE);
$output1_s = curl_exec($ch1_s); 




$json_s = json_decode($output1_s, true);

$completed = $json_s["completed"];
$failed= $json_s["failed"];
$total = $json_s["total"];




// check if job id is in progress 

if ($completed == '0' && $failed == '0' && $total == '1') {
    //echo 'true';

echo "<div id='alertdata_v_sas'>$output1</div><br>";
echo "<div id='alertdata_v1_sas' style='background:#800000;color:white;padding:10px;border:none;'>
Job is in Progress or in Queue. They might be 30 Seconds to 1 Minutes Latency in the Model dues Jobs Queue. 
Please Try Submitting Data in the Next 10 Seconds</div><br>";

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
<center><span style='font-size:30px;color:#800000' id='timer_sec' class='alertdata_v2_sas'>x</span></center>
";


exit();
}



$jobid_s = $json_s["jobIdentifier"];
$summary = $json_s["results"]["my-input"]["results.json"]["summary"];

echo "<br><h2>Content Summarization</h2>";

echo "<div style='background:green;color:white;padding:10px;border:none'> Content Summarization Successful....</div><br>";

echo "$summary";







?>




























