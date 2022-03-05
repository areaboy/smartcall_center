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
                $('#submit_summary_btn').click(function () {

                    var jobid = $('#jobid_sas').val();
 var y_uid = $('#y_uid_sas').val();
 var y_message = $('#y_message_sas').val();
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
                    $('#loader_xe_sas').fadeIn(400).html('<br><div class="well" style="color:black"><img src="loader.gif" width="50px" height="50px"> &nbsp;Please Wait, Finally Processing Your Data  for Summary Analysis</div>');
                    $.ajax({
                        url: 'modzy_summary_action.php',
                        data: form_data,
                        processData: false,
                        contentType: false,
                        ache: false,
                        type: 'POST',
           
                        success: function (msg) {
                                $('#box').val('');
				$('#loader_xe_sas').hide();
				$('.result_data_xe_sas').html(msg);
				$('#alertdata_uploadfiles').delay(5000).fadeOut('slow');
                                $('#fade-way').delay(5000).fadeOut('slow');
                                $('#alertdata_uploadfiles2').delay(20000).fadeOut('slow');
                               // $('#audio_btne').removeAttr('disabled');


$('#alertdata_v_sas').delay(12000).fadeOut('slow');
$('#alertdata_v1_sas').delay(12000).fadeOut('slow');
$('#alertdata_v2_sas').delay(12000).fadeOut('slow');

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




$data_param_summary= '
{
  "model": {
    "identifier": "rs2qqwbjwb",
    "version": "0.0.2"
  },
  "input": {
    "type": "text",
    "sources": {
      "my-input": {
         "input.txt": "'.$notes.'"
      }
    }
  }
}';




$url_s ='https://app.modzy.com/api/jobs';

$ch_s = curl_init();
curl_setopt($ch_s, CURLOPT_URL, $url_s);
curl_setopt($ch_s, CURLOPT_POST, TRUE);
curl_setopt($ch_s, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: ApiKey $token_modzy"));  
curl_setopt($ch_s, CURLOPT_POSTFIELDS, $data_param_summary);
//curl_setopt($ch_s, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch_s, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch_s, CURLOPT_RETURNTRANSFER, TRUE);
$output_s = curl_exec($ch_s); 


echo $output_s;



$json = json_decode($output_s, true);
//echo $id = $json["model"]["identifier"];
$jobid = $json["jobIdentifier"];
//echo $accesskey = $json["accessKey"];






if($jobid !=''){
echo "<div style='background:green;color:white;padding:10px;border:none'>Summary Job ID Created Successfully.<br> You can now Run
Data Summarization Analysis.<br></div><br>";




$postid_x = '100';




echo "<div style='background:#ddd;'>
<script>

$(document).ready(function(){
$('.hide_summary_btn').hide();
   });

//$('#file_content').val('');
//$('#title').val('');

</script>


<!--start form-->
<form id='' method='post'>

<div class='form-group'>
<label style='font-size:16px;padding:6px;'>Click the Link Below to finalize  Summary  Analysis</label><br>
<input  type='hidden' id='jobid_sas' name='jobid_sas' value='$jobid'/>
<input  type='hidden' id='y_uid_sas' name='y_uid_sas' value='$uid '/>
<input  type='hidden' id='y_message_sas' name='y_message_sas' value='$messages'/>

</div>

                    <div class='form-group'>
                           
                        <div id='loader_xe_sas'></div>
                        <div class='result_data_xe_sas'></div>
                    </div>

                    <input type='button' id='submit_summary_btn' class='c_btn' value='Finalize Summary Analysis Now' />
                </form>

<!--end form-->



</div><br><br>";




}else{

echo "<div style='background:red;color:white;padding:10px;border:none'> Summarization Job ID Retrival Failed. Please Ensure Internet Connections...</div><br>";
exit();
}




?>