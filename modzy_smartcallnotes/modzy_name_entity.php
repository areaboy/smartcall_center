<?php

error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);



?>





 <script>


            $(function () {
                $('#audio_btne').click(function () {
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

                   // $('.upload_progress').css('width', '0');
                    $('#btn').attr('disabled', 'disabled');
                    $('#loader_xe').fadeIn(400).html('<br><div class="well" style="color:black"><i class="fa fa-spinner fa-spin" style="font-size:20px"></i> &nbsp;Please Wait, Finally Processing Your Entity Name Data</div>');
                    $.ajax({
                        url: 'modzy_name_entity2.php',
                        data: form_data,
                        processData: false,
                        contentType: false,
                        ache: false,
                        type: 'POST',
           
                        success: function (msg) {
                                $('#box').val('');
				$('#loader_xe').hide();
				$('.result_data_xe').html(msg);
				$('#alertdata_uploadfiles').delay(5000).fadeOut('slow');
                                $('#fade-way').delay(5000).fadeOut('slow');
                                $('#alertdata_uploadfiles2').delay(20000).fadeOut('slow');
                               // $('#audio_btne').removeAttr('disabled');


$('#alertdata_v').delay(12000).fadeOut('slow');
$('#alertdata_v1').delay(12000).fadeOut('slow');
$('#alertdata_v2').delay(12000).fadeOut('slow');

                        }
                    });
} // end if validate
                });
            });

</script>






<?php


$title = strip_tags($_POST['title']);
$uid = strip_tags($_POST['uid']);
$messages = strip_tags($_POST['messages']);
$postid = strip_tags($_POST['postid']);


// Remove any line space
$notes = str_replace(array("\r", "\n"), '', $messages);


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



$data_param_entity='{
  "model": {
    "identifier": "a92fc413b5",
    "version": "0.0.12"
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




$url_e ='https://app.modzy.com/api/jobs';

$ch_e = curl_init();
curl_setopt($ch_e, CURLOPT_URL, $url_e);
curl_setopt($ch_e, CURLOPT_POST, TRUE);
curl_setopt($ch_e, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: ApiKey $token_modzy"));  
curl_setopt($ch_e, CURLOPT_POSTFIELDS, $data_param_entity);
//curl_setopt($ch_e, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch_e, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch_e, CURLOPT_RETURNTRANSFER, TRUE);
$output_e = curl_exec($ch_e); 

echo $output_e;


$json_e = json_decode($output_e, true);
$jobid_e = $json_e["jobIdentifier"];



$timer = time();
include('data6rst.php');


if($jobid_e !=''){
echo "<div style='background:green;color:white;padding:10px;border:none'>Name Entity Recognitions Job ID Created Successfully.<br> You can now Run
Name Entity Contents Recognitions.<br></div><br>";




$postid_x = '100';




echo "<div style='background:#ddd;'>
<script>

$(document).ready(function(){
$('.data_hide2').hide();
   });

//$('#file_content').val('');
//$('#title').val('');

</script>


<!--start form-->
<form id='' method='post'>

<div class='form-group'>
<label style='font-size:16px;padding:6px;'>Click the Link Below to finalize Entity Name Analysis</label><br>
<input  type='hidden' id='uid' name='uid' value='$uid'/>
<input  type='hidden' id='jobid' name='jobid' value='$jobid_e'/>
<input  type='hidden' id='postid' name='postid' value='$postid_x'/>
</div>

                    <div class='form-group'>
                           
                        <div id='loader_xe'></div>
                        <div class='result_data_xe'></div>
                    </div>

                    <input type='button' id='audio_btne' class='c_btn' value='Finalize Entity Name Analysis Now' />
                </form>

<!--end form-->



</div><br><br>";




}else{

echo "<div style='background:red;color:white;padding:10px;border:none'> Content Name Entity Recognitions Job ID Retrival Failed. Ensure there is Internet Connections....</div><br>";
exit();
}






?>



