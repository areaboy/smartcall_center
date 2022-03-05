<?php

error_reporting(0);
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);


          //form_data.append('jobid', jobid);

          //form_data.append('y_uid', y_uid);
//form_data.append('y_message', y_message);



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





$url1 ="https://app.modzy.com/api/results/$jobid";

$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, $url1);
//curl_setopt($ch1, CURLOPT_POST, TRUE);
curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json', "Authorization: ApiKey $modzy_apikey"));  
//curl_setopt($ch1, CURLOPT_POSTFIELDS, $data_param);
curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");
//curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE);
$output1 = curl_exec($ch1); 

//echo $output1;




$json = json_decode($output1, true);

$completed = $json["completed"];

$failed= $json["failed"];
$total = $json["total"];



// check if job id is in progress 

if ($completed == '0' && $failed == '0' && $total == '1') {
    //echo 'true';

echo "<div id='alertdata_v_s'>$output1</div><br>";
echo "<div id='alertdata_v1_s' style='background:#800000;color:white;padding:10px;border:none;'>
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
<center><span style='font-size:30px;color:#800000' id='timer_sec' class='alertdata_v2_s'>x</span></center>
";


exit();
}






$jobid = $json["jobIdentifier"];
$rs = $json["results"]["first-phone-call"]["status"];
$rs1 = $json["results"]["first-phone-call"]["results.json"]["data"]["result"]["classPredictions"];





$timer = time();
include('data6rst.php');



if($completed=='1'){
echo "<div style='background:green;color:white;padding:10px;border:none'>Sentimental Analysis Successful</div>";

foreach($rs1 as $row){


$class = $row["class"];
$score = $row["score"];


$statement = $db->prepare('INSERT INTO sentiments
(jobid,sentiments,score,timing,userid)
 
                          values
(:jobid,:sentiments,:score,:timing,:userid)');

$statement->execute(array( 

':jobid' => $jobid,
':sentiments' => $class,
':score' => $score,		
':timing' => $timer,
':userid' => $uid

));




}


}else{

echo "<div style='background:red;color:white;padding:10px;border:none'> Sentimental Analysis Failed. Ensure Internet Connection and  Please Try again...</div>";
exit();
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
</style>



<br><br>






<div class='col-sm-12'>

        <div class="container">

            <?php
			
$res= $db->prepare("SELECT * FROM sentiments where jobid =:jobid order by score desc");
$res->execute(array(':jobid' =>$jobid));
$totalcount = $res->rowCount();


echo "<h3> <center>Data Sentimental Analysis</center></h3>";
echo '<table style="width:60%;" border="0" cellspacing="2" cellpadding="2" class="table table-striped_no table-bordered table-hover"> 
      <tr> 
	  
          <th> <font face="Arial">Sentiments</font> </th> 
          <th> <font face="Arial">Scores (Degree of Sentiments)</font> </th> 
          <th> <font face="Arial">Sentiments Image Analysis</font> </th> 


      </tr>';


while($row = $res->fetch()){

                $id= $row['id'];
                 //$posts = $row['posts'];
                $sentiments = $row['sentiments'];
                $score = $row['score'];
$score1  = $score * 100;
                $timing = $row['timing'];
            

if($sentiments == 'positive'){

$st = $sentiments;
$colorx ="green_css";


$image = 'happy.png';
$sst = 'Happy';
}
if($sentiments == 'neutral'){

$st = $sentiments;
$colorx ="pink_css";
$image = 'neutral.png';
$sst = 'Mild';
}

if($sentiments == 'negative'){

$st = $sentiments;
$colorx ="red_css";
$image = 'sad.png';
$sst = 'Sad';
}
            ?>

                <div class='post'  id="post_<?php echo $id; ?>">


<?php
if($sentiments){
?>

<tr> 

                  
                  <td class="<?php echo $colorx; ?>"><?php echo $sentiments; ?></td> 
 <td><?php echo $score; ?> (<?php echo $score1; ?>% <?php echo $sentiments; ?>)</td> 

                  <td><img class='' style='border-style: solid; border-width:3px; border-color:#8B008B; width:40px;height:40px; 
max-width:40px;max-height:40px;border-radius: 40%;' src='sentiments_image/<?php echo $image; ?>' title='Image'> (<?php echo $sst; ?>)</td> 

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









<style>
/*
body {
    width: 660px;
    margin: 0 auto;
}
*/
</style>




<script type="text/javascript">  

google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(column_chart);
function column_chart() {

$('#loader1').fadeIn(400).html('<div style="background:#ddd;color:black;padding:10px;"><i class="fa fa-spinner fa-spin" style="font-size:20px"></i> &nbsp; &nbsp;Please Wait,  Statistics is being Loaded.</div>');

var st ='<?php echo $jobid; ?>';
var res = $.ajax({
url: 'chart_ok.php',
data:{st:st},
dataType:"json",
async: false,
success: function(res)
{

  var options = {'title':'Statistical Graphs of Sentiments Vs Score', 'width':800, 'height':400,
 series: {
            0: { color: 'purple' },
            1: { color: 'navy' },
          
          }
};


var data = new google.visualization.arrayToDataTable(res);
var chart = new google.visualization.ColumnChart(document.getElementById('columnchart_data'));
chart.draw(data, options);
$('#loader1').hide();

}
}).responseText;
}




google.charts.setOnLoadCallback(line_chart);
function line_chart() {


$('#loader2').fadeIn(400).html('<div style="background:#ddd;color:black;padding:10px;"><i class="fa fa-spinner fa-spin" style="font-size:20px"></i> &nbsp; &nbsp;Please Wait, Statistics is being Loaded</div>');

var st ='<?php echo $jobid; ?>';
var res1 = $.ajax({
url: 'chart_ok.php',
data:{st:st},
dataType:"json",
async: false,
success: function(res1)
{

  var options = {'title':'Statistical Graphs of Sentiments Vs Score', 'width':800, 'height':400,
 series: {
            0: { color: '#800000' },
            1: { color: 'orange' },
          
          }
};


var data = new google.visualization.arrayToDataTable(res1);
var chart = new google.visualization.AreaChart(document.getElementById('areachart_data'));
chart.draw(data, options);
$('#loader2').hide();

}
}).responseText;
}







google.charts.setOnLoadCallback(pie_chart);
function pie_chart() {


var st ='<?php echo $jobid; ?>';
$('#loader3').fadeIn(400).html('<div style="background:#ddd;color:black;padding:10px;"><i class="fa fa-spinner fa-spin" style="font-size:20px"></i> &nbsp; &nbsp;Please Wait,  Statistics is being Loaded</div>');

var res2 = $.ajax({
url: 'chart_ok.php',
data:{st:st},
dataType:"json",
async: false,
success: function(res2)
{

  var options = {'title':'Statistical Graphs of Sentiments Vs Score', 'width':800, 'height':400,
 series: {
            0: { color: '#800000' },
            1: { color: 'orange' },
          
          }
};


var data = new google.visualization.arrayToDataTable(res2);
var chart = new google.visualization.PieChart(document.getElementById('piechart_data'));
chart.draw(data, options);
$('#loader3').hide();

}
}).responseText;
}



</script>

<br><br>
<h3><center>Statistical Graphs of Data Sentiments vs Scores..</center></h3>
<br>

<div id="loader1"></div>
    <div id="areachart_data"></div>

<div id="loader2"></div>
    <div id="columnchart_data"></div>



<div id="loader3"></div>
    <div id="piechart_data"></div>



    </div>



<div id="loader" class='res_center_css'></div>

































