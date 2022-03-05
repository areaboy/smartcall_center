<?php
//error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 

ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);


?>



  <script>


            $(function () {
                $('#audio_btn').click(function () {
                    var uid = $('#uid').val();
                    var jobid = $('#jobid').val();
					var postid = $('#postid').val();
                  
//alert(uid);
//alert(jobid);
//alert(postid);
// start if validate
if(uid==""){
alert('There is Problem with Userid');
}
else if(jobid==""){
alert('There is problem with your Job Id');
}

else if(postid==""){
alert('There is problem with your Post Id');
}

else{
          var form_data = new FormData();
          form_data.append('uid', uid);
          form_data.append('jobid', jobid);
		  form_data.append('postid', postid);

                    $('.upload_progress').css('width', '0');
                    $('#btn').attr('disabled', 'disabled');
                    $('#loader_x').fadeIn(400).html('<br><div class="well" style="color:black"><i class="fa fa-spinner fa-spin" style="font-size:20px"></i> &nbsp;Please Wait, Processing Your Voice Data</div>');
                    $.ajax({
                        url: 'audio_convert.php',
                        data: form_data,
                        processData: false,
                        contentType: false,
                        ache: false,
                        type: 'POST',
           
                        success: function (msg) {
                                $('#box').val('');
				$('#loader_x').hide();
				$('.result_data_x').fadeIn('slow').prepend(msg);
				$('#alertdata_uploadfiles').delay(5000).fadeOut('slow');
                                $('#alerts_reg').delay(5000).fadeOut('slow');
                                $('#alertdata_uploadfiles2').delay(20000).fadeOut('slow');
                               // $('#audio_btn').removeAttr('disabled');

$('#alertdata_v').delay(12000).fadeOut('slow');
$('#alertdata_v1').delay(12000).fadeOut('slow');
$('.alertdata_v2').delay(12000).fadeOut('slow');


                        }
                    });
} // end if validate
                });
            });

</script>



<?php

if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

$file_url = strip_tags($_POST['file_url']);
$filename = strip_tags($_POST['filename']);

$uid = strip_tags($_POST['userid']);
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




$title = strip_tags($_POST['title']);
$userid = strip_tags($_POST['userid']);
$ownerid = strip_tags($_POST['ownerid']);

$mt_id=rand(0000,9999);
$dt2=date("Y-m-d H:i:s");
$ipaddress = strip_tags($_SERVER['REMOTE_ADDR']);

$timer= time();
$ids = $timer.$ipaddress;

$identity = md5($ids);
$idy = $identity.$timer;



if ($file_url == ''){
echo "<div style='background:red;border:none;color:white;padding:10px;' id='alerts_reg'>Files Url is empty</div>";
exit();
}


if ($title == ''){
echo "<div style='background:red;border:none;color:white;padding:10px;' id='alerts_reg'>Audio Title name is empty</div>";
exit();
}


$ip= filter_var($ipaddress, FILTER_VALIDATE_IP);
if (!$ip){
echo "<div style='background:red;border:none;color:white;padding:10px;' id='alerts_reg'>IP Address is Invalid</div>";
exit();
}








//include database
include('data6rst.php');




//insert into database
$final_filename =  $file_url;
$timerx =time();
include("time/now.fn");
$created_time=strip_tags($now);


//Rev.ai Voice Call


$audio_file =$file_url;
$data_param="{\"media_url\":\"$audio_file\",\"metadata\":\"This is a sample submit jobs option\"}";



$url ='https://api.rev.ai/speechtotext/v1/jobs';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $revai_apikey", 'Content-Type: application/json'));  
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_param);
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$output = curl_exec($ch); 

//echo $output;


$json = json_decode($output, true);
$jobid = $json["id"];
$fillename = $json["name"];
$status = $json["status"];

if($jobid == ''){


echo "<div id='alertdata_uploadfiles_o' style='background:red;color:white;padding:10px;border:none;'>
There is an Issue Getting Rev.Ai Job Id. Please Ensure there is Internet Connections</div><br>";
exit();
}




$statement = $db->prepare('INSERT INTO audio_calls
(audio_title,audio_name,messages,created_time,user_id,owner_identity,status,job_id,identity)
 
                          values
(:audio_title,:audio_name,:messages,:created_time,:user_id,:owner_identity,:status,:job_id,:identity)');

$statement->execute(array( 

':audio_title' => $title,
':audio_name' => $filename,
':messages' => 'none',		
':created_time' =>$timer,
':user_id' =>$userid,
':owner_identity' => $ownerid,
':job_id' => $jobid,
':status' => '0',
':identity' => $idy

));



$stmt = $db->query("SELECT LAST_INSERT_ID()");
$lastId = $stmt->fetchColumn();






if($statement){
echo "<div id='alertdata_uploadfiles_o' style='background:green;color:white;padding:10px;border:none;'>
Job Id Created Successfully. Its Time to Convert Audio Calls to Text</div><br><br>";



echo "<div style='background:#ddd;'>
<script>

$(document).ready(function(){
$('.data_hide').hide();
   });

$('#file_url').val('');
$('#title_url').val('');

</script>

<h2><center>Voice Call Speech to Text Conversion</center></h2>
<br>


<!--start form-->
<form id='' method='post'>

<div class='form-group'>
<label style='font-size:16px;padding:6px;'>Click the Link Below to Convert Audio Calls to Text </label><br>
<input  type='hidden' id='uid' name='uid' value='$userid'/>
<input  type='hidden' id='jobid' name='jobid' value='$jobid'/>
<input  type='hidden' id='postid' name='postid' value='$lastId'/>
</div>

                    <div class='form-group'>
                           
                        <div id='loader_x'></div>
                        <div class='result_data_x'></div>
                    </div>

                    <input type='button' id='audio_btn' class='c_btn' value='Convert Audio Call to Text Now' />
                </form>

<!--end form-->



</div><br><br>";

/*
echo "<script>
window.setTimeout(function() {
    window.location.href = 'login.html';
}, 1000);
</script><br><br>";
*/



                }
else {

echo "<div id='alertdata_uploadfiles_o' style='background:red;color:white;padding:10px;border:none;'>
Data Creation Failed.</div><br>";
                }   









}



?>



