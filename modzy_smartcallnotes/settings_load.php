<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



$userid = strip_tags($_POST['useridxa']);
$ownerid = strip_tags($_POST['owneridxa']);
?>


<script>


            
$(document).ready(function(){
                $('#settings_btn').click(function () {
                    var revai_apikey = $('#revai_apikey').val();
                    var modzy_apikey = $('#modzy_apikey').val();
                    var site_url =     $('#site_url').val();

                  
var useridx = $('#uidx').val();
alert(useridx);

// start if validate
if(revai_apikey==""){
alert('Rev.AI Apikey cannot be Empty');
}

else if(modzy_apikey==""){
alert('Modzy APIkey Cannot be Empty');
}

else if(site_url==""){
alert('Site URL Cannot be Empty');
}

else{
          var form_data = new FormData();
          form_data.append('revai_apikey', revai_apikey);
          form_data.append('modzy_apikey', modzy_apikey);
          form_data.append('site_url', site_url);
          form_data.append('useridx', useridx);
              
                    //$('#settings_btn').attr('disabled', 'disabled');
                    $('#loader_settings').fadeIn(400).html('<br><span class="well" style="color:black"><i class="fa fa-spinner fa-spin" style="font-size:20px"></i> &nbsp;Please Wait, Settings is being Updated</span>');
                    $.ajax({
                        url: 'settings_updates.php',
                        data: form_data,
                        crossDomain: true,
                        processData: false,
                        contentType: false,
                        cache: false,
                        type: 'POST',
                        
                        success: function (msg) {
                                $('#box').val('');
				$('#loader_settings').hide();
				$('.result_settings').fadeIn('slow').prepend(msg);
				$('#alertdata_uploadfiles').delay(5000).fadeOut('slow');
                                




                        }
                    });
} // end if validate
                });
            });

</script>




<?php


include('data6rst.php');


$result1 = $db->prepare('SELECT * FROM settings where user_id = :user_id');
$result1->execute(array(':user_id' => $userid));
$nosofrows1 = $result1->rowCount();
$rows = $result1->fetch();
if ($nosofrows1 == 0)
{
echo "<br><div style='background:red;border:none;color:white;padding:10px;''  id='alertdata_uploadfiles'>Settings Not Updated Yet</div>";
exit();
}

$modzy_apikey = $rows['modzy_apikey'];
$revai_apikey = $rows['revai_apikey'];
$site_url = $rows['site_url'];



$modzy_apikey1 = substr($modzy_apikey, 0, 10)."...";  
$revai_apikey1 = substr($revai_apikey, 0, 10)."...";


echo "<div style='background:#ddd;'>




<h2><center>Update Application Settings </center></h2>
<br>
<b>Rev.AI Apikey :</b> $revai_apikey1<br>
<b>Modzy AI Apikey :</b> $modzy_apikey1<br>
<b>Site Project URL  :</b> $site_url<br>

<!--start form-->
<form id='' method='post'>

<div class='form-group'>
<label style='font-size:16px;padding:6px;'>Rev.AI Apikey </label><br>
<input class='col-sm-12 form-control'  type='text' id='revai_apikey' name='revai_apikey' value='$revai_apikey'/>
</div>



<div class='form-group'>
<label style='font-size:16px;padding:6px;'>Modzy API Key</label><br>
<input class='col-sm-12 form-control'  type='text' id='modzy_apikey' name='modzy_apikey' value='$modzy_apikey'/>

</div>



<div class='form-group'>
<label style='font-size:16px;padding:6px;'>Site Projects URL </label><br>
 Eg.( &nbsp;&nbsp;&nbsp; https://fredjarsoft.com/modzy_callnotes  &nbsp;&nbsp;&nbsp;)<br>
<input class='col-sm-12 form-control'  type='text' id='site_url' name='site_url' value='$site_url'/>

</div>
<input  type='hidden' id='uidx' name='uidx' value='$userid'/>

                    <div class='form-group'>
                           
                        <div id='loader_settings'></div>
                        <div class='result_settings'></div>
                    </div>

                    <input type='button' id='settings_btn' class='c_btn' value='Update Settings Now' />
                </form>

<!--end form-->



</div><br><br>";






?>