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

$file_content = strip_tags($_POST['file_fname']);
//$username1 = strip_tags($_POST['username']);
//$username = str_replace(' ', '', $username1);

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

// honey pot spambots
$emailaddress_pot =$_POST['emailaddress_pot'];
if($emailaddress_pot !=''){
//spamboot detected.
//Redirect the user to google site

echo "<script>
window.setTimeout(function() {
    window.location.href = 'https://google.com';
}, 1000);
</script><br><br>";

exit();
}


if ($file_content == ''){
echo "<div style='background:red;border:none;color:white;padding:10px;' id='alerts_reg'>Files Upload is empty</div>";
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





$filename_string = strip_tags($_FILES['file_content']['name']);

// thus check files extension names before major validations

$allowed_formats = array("mp3", "MP3", "flac", "FLAC");
$exts = explode(".",$filename_string);
$ext = end($exts);

if (!in_array($ext, $allowed_formats)) { 
echo "<div id='alertdata_uploadfiles' style='background:red;border:none;color:white;padding:10px;'>File Formats not allowed. only .flac,. mp3 audio files are allowed.<br></div>";
exit();
}




$upload_path = "uploads/";

$fsize = $_FILES['file_content']['size']; 
$ftmp = $_FILES['file_content']['tmp_name'];

//give file a random names
$filecontent_name = $userid.$mt_id.time();

if ($fsize > 40 * 1024 * 1024) { // allow file of less than 40 mb
echo "<div id='alertdata' style='background:red;border:none;color:white;padding:10px;'>File greater than 40mb not allowed<br></div>";
exit();
}


$allowed_types=array(

'audio/mpeg',
'AUDIO/MPEG',
'audio/x-flac',
'AUDIO/X-FLAC',
'audio/flac',
'AUDIO/FLAC',
'AUDIO/WAV',
'audio/wav',
'AUDIO/X-WAV',
'audio/x-wav'


);




if ( ! ( in_array($_FILES["file_content"]["type"], $allowed_types) ) ) {

  echo "<div id='alertdata_uploadfiles' style='background:red;border:none;color:white;padding:10px;'> Only .flac, .mp3 audio Files are allowed..<br><br></div>";

exit();

}





//validate file using file info  method
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $_FILES['file_content']['tmp_name']);


if ( ! ( in_array($mime, $allowed_types) ) ) {

  echo "<div id='alertdata_uploadfiles' style='background:red;border:none;color:white;padding:10px;'>Only .flac, mp3 audio Files are allowed..<br></div>";

exit();

}
finfo_close($finfo);





//include database
include('data6rst.php');

/*
$result1 = $db->prepare('SELECT  FROM users where email = :email');
$result1->execute(array(':email' => $email));
$nosofrows1 = $result1->rowCount();
//if ($nosofrows1 == 1)
//if ($nosofrows1 != 0)
if ($nosofrows1 > 0)
{
echo "<br><div style='background:red;border:none;color:white;padding:10px;''  id='alertdata_uploadfiles'>This Email is already taken</div>";
exit();
}

*/


if (move_uploaded_file($ftmp, $upload_path . $filecontent_name.'.'.$ext)) {



//insert into database
$final_filename =  $filecontent_name.'.'.$ext;
$timerx =time();
include("time/now.fn");
$created_time=strip_tags($now);


//Rev.ai Voice to text conversion

$audio_file ="$site_url/uploads/$final_filename";


echo $data_param="{\"media_url\":\"$audio_file\",\"metadata\":\"This is a sample submit jobs option\"}";



//$data_param="{\"media_url\":\"https://www.rev.ai/FTC_Sample_1.mp3\",\"metadata\":\"This is a sample submit jobs option\"}";



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

echo $output;


$json = json_decode($output, true);
echo $jobid = $json["id"];
echo $fillename = $json["name"];
echo $status = $json["status"];

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
':audio_name' => $final_filename,
':messages' => '$message',		
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

$('#file_content').val('');
$('#title').val('');

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

                }
else {

echo "<div id='alertdata_uploadfiles_o' style='background:red;color:white;padding:10px;border:none;'>
Data Creation Failed.</div><br>";
                }   




}



?>



