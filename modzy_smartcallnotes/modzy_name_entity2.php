<?php

//error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);



$uid = strip_tags($_POST['uid']);
 $jobid = strip_tags($_POST['jobid']);
$postid = strip_tags($_POST['postid']);

//echo "<br><br>my Job:( $jobid)<br><br>";
//echo "user:( $uid)<br><br>";

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



//$jobid_trim = trim($jobid);
$url1_e ="https://app.modzy.com/api/results/$jobid";

$ch1_e = curl_init();
curl_setopt($ch1_e, CURLOPT_URL, $url1_e);
//curl_setopt($ch1_e, CURLOPT_POST, TRUE);
curl_setopt($ch1_e, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json', "Authorization: ApiKey $token_modzy"));  
//curl_setopt($ch1_e, CURLOPT_POSTFIELDS, $data_param);
curl_setopt($ch1_e, CURLOPT_CUSTOMREQUEST, "GET");
//curl_setopt($ch1_e, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch1_e, CURLOPT_RETURNTRANSFER, TRUE);
$output1_e = curl_exec($ch1_e); 



$json1_e = json_decode($output1_e, true);
$completed_e = $json1_e["completed"];
$failed_e = $json1_e["failed"];
$total_e = $json1_e["total"];


$timer = time();
include('data6rst.php');



// check if job id is in progress 

if ($completed_e == '0' && $failed_e == '0' && $total_e == '1') {
    //echo 'true';

echo "<div id='alertdata_v'>$output1_e</div><br>";
echo "<div id='alertdata_v1' style='background:#800000;color:white;padding:10px;border:none;'>
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
<center><span style='font-size:30px;color:#800000' id='timer_sec' class='alertdata_v2'>x</span></center>
";


exit();
}



if($completed_e=='1'){
echo "<div style='background:green;color:white;padding:10px;border:none'>Contents Name Entity Recognitions Successful</div><br>";

}else{

echo "<div id ='fade-way' style='background:red;color:white;padding:10px;border:none'> Content Name Entity Recognitions Failed. Please Try Again</div><br>";
exit();
}


$jobid = $json1_e["jobIdentifier"];
$res = $json1_e["results"]["my-input"]["results.json"];

foreach($res as $row){
//echo $class = $row;
$words = $row[0];
$entity = $row[1];


if($entity =='B-LOC'){
$entity_desc ='Locations';

}

if($entity =='I-LOC'){
$entity_desc ='Locations';

}


if($entity =='B-PER'){
$entity_desc ='Persons Name';

}


if($entity =='I-PER'){
$entity_desc ='Persons Name';

}




if($entity =='B-ORG'){
$entity_desc ='Organization Name';

}


if($entity =='I-ORG'){
$entity_desc ='Organization Name';

}




if($entity =='B-MISC'){
$entity_desc ='Miscellaneous Name';

}


if($entity =='I-MISC'){
$entity_desc ='Miscellaneous Name';

}



if($entity =='O'){
$entity_desc ='Oustide Entity Name';

}


$statement = $db->prepare('INSERT INTO name_entity
(jobid,words,entity,entity_desc,timing,userid,status)
 
                          values
(:jobid,:words,:entity,:entity_desc,:timing,:userid,:status)');

$statement->execute(array( 

':jobid' => $jobid,
':words' => $words,
':entity' => $entity,	
':entity_desc' => $entity_desc,	
':timing' => $timer,
':userid' => $uid,
':status' => '0'

));


$stmt = $db->query("SELECT LAST_INSERT_ID()");
$lastId = $stmt->fetchColumn();


}







?>







<style>

.red_css{
background: red;
color:white;
padding:8px;
border-radius:15%;
text-align:center;
}


.green_css{
background: green;
color:white;
padding:8px;
border-radius:15%;
text-align:center;
}



.pink_css{
background: purple;
color:white;
padding:8px;
border-radius:15%;
text-align:center;

}

.ccp_css{
background:purple;color:white;padding:8px;border:none;border-radius:15%;

}
.ccp_css:hover{
background: orange;
color:black;
}
</style>



<br><br>



 

<div class='col-sm-12'>

        <div class="container">

            <?php

			
$res= $db->prepare("SELECT * FROM name_entity where jobid =:jobid and entity !=:entity order by id desc");
$res->execute(array(':jobid' =>$jobid, ':entity'=> 'O'));
$totalcount = $res->rowCount();


echo "<h3> <center>Data Entity Name Analysis</center></h3>";
echo '<table style="width:60%;" border="0" cellspacing="2" cellpadding="2" class="table table-striped_no table-bordered table-hover"> 
      <tr> 
	  
          <th> <font face="Arial">Entites</font> </th> 
          <th> <font face="Arial">Entities Class</font> </th> 
          <th> <font face="Arial">Entities Name Analysis</font> </th> 


      </tr>';


while($row = $res->fetch()){

                $id= $row['id'];
                 //$posts = $row['posts'];
                $words = $row['words'];
                $entity = $row['entity'];
                $entity_desc = $row['entity_desc'];
            
            ?>

                <div class='post'  id="post_<?php echo $id; ?>">


<?php
if($words){
?>

<tr> 

                  
 <td class='ccp_css'><?php echo $words; ?></td> 
<td ><?php echo $entity; ?></td> 
 <td><?php echo $entity_desc; ?></td>

              </tr>

<?php } ?>
                   
                </div>

            <?php
            }
            ?>
</table>

        </div>
</div>


<br>

<h2><center>Outside Entities Name</center></h2>
<?php
$res2= $db->prepare("SELECT * FROM name_entity where jobid =:jobid and entity =:entity order by id desc");
$res2->execute(array(':jobid' =>$jobid, ':entity'=> 'O'));

echo "<div>";
while($row = $res2->fetch()){

                $id= $row['id'];
                $words = $row['words'];
                $entity = $row['entity'];
                $entity_desc = $row['entity_desc'];

echo "<span text-align:center' class='col-sm-3 ccp_css'>$words($entity)</span>";

}
echo "</div>";
?>
