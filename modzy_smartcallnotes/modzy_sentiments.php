<?php

error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);



$title = strip_tags($_POST['title']);
$uid = strip_tags($_POST['uid']);
$messages = strip_tags($_POST['messages']);
$postid = strip_tags($_POST['postid']);

?>



 <script>


            $(function () {
                $('#submit_sentiments_btn').click(function () {

                    var jobid = $('#jobid_s').val();
 var y_uid = $('#y_uid_s').val();
 var y_message = $('#y_message_s').val();
 var uid = '<?php echo $uid; ?>';


/*	
alert(jobid);
alert(y_uid);
alert(y_message);
*/				
                  
// start if validate
 if(jobid==""){
alert('There is problem with your Job Id');
}


else{

          var form_data = new FormData();

          form_data.append('jobid', jobid);
          form_data.append('y_uid', y_uid);
          form_data.append('y_message', y_message);
	form_data.append('uid', uid);

                   // $('.upload_progress').css('width', '0');
                    $('#btn').attr('disabled', 'disabled');
                    $('#loader_xe_s').fadeIn(400).html('<br><div class="well" style="color:black"><img src="loader.gif" width="50px" height="50px"> &nbsp;Please Wait, Finally Processing Your Data  for Sentimental Analysis</div>');
                    $.ajax({
                        url: 'modzy_sentiments_action.php',
                        data: form_data,
                        processData: false,
                        contentType: false,
                        ache: false,
                        type: 'POST',
           
                        success: function (msg) {
                                $('#box').val('');
				$('#loader_xe_s').hide();
				$('.result_data_xe_s').html(msg);
				$('#alertdata_uploadfiles').delay(5000).fadeOut('slow');
                                $('#fade-way').delay(5000).fadeOut('slow');
                                $('#alertdata_uploadfiles2').delay(20000).fadeOut('slow');
                               // $('#audio_btne').removeAttr('disabled');


$('#alertdata_v_s').delay(12000).fadeOut('slow');
$('#alertdata_v1_s').delay(12000).fadeOut('slow');
$('#alertdata_v2_s').delay(12000).fadeOut('slow');

                        }
                    });
} // end if validate
                });
            });

</script>




<?php
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



// remove any line space

$notes = str_replace(array("\r", "\n"), '', $messages);

$token_modzy = $modzy_apikey;


$data_param=
'{
      "model": {
        "identifier": "ed542963de",
        "version": "1.0.1"
      },
      "input": {
        "type": "text",
        "sources": {
          "first-phone-call": {
            "input.txt": "'.$notes.'"
          }
        }
      }
    }';




$url ='https://app.modzy.com/api/jobs';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: ApiKey $token_modzy"));  
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_param);
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$output = curl_exec($ch); 

echo $output;



$json = json_decode($output, true);
//echo $id = $json["model"]["identifier"];
$jobid = $json["jobIdentifier"];
//echo $accesskey = $json["accessKey"];






if($jobid !=''){
echo "<div style='background:green;color:white;padding:10px;border:none'>Sentiments Job ID Created Successfully.<br> You can now Run
Data Sentimental Analysis.<br></div><br>";




$postid_x = '100';




echo "<div style='background:#ddd;'>
<script>

$(document).ready(function(){
$('.hide_sentiments_btn').hide();
   });

//$('#file_content').val('');
//$('#title').val('');

</script>


<!--start form-->
<form id='' method='post'>

<div class='form-group'>
<label style='font-size:16px;padding:6px;'>Click the Link Below to finalize Sentimental  Analysis</label><br>
<input  type='hidden' id='jobid_s' name='jobid_s' value='$jobid'/>
<input  type='hidden' id='y_uid_s' name='y_uid_s' value='$uid '/>
<input  type='hidden' id='y_message_s' name='y_message_s' value='$messages'/>

</div>

                    <div class='form-group'>
                           
                        <div id='loader_xe_s'></div>
                        <div class='result_data_xe_s'></div>
                    </div>

                    <input type='button' id='submit_sentiments_btn' class='c_btn' value='Finalize Sentimental Analysis Now' />
                </form>

<!--end form-->



</div><br><br>";




}else{

echo "<div style='background:red;color:white;padding:10px;border:none'> Sentiments Job ID Retrival Failed. Please Ensure Internet Connections...</div><br>";
exit();
}




?>