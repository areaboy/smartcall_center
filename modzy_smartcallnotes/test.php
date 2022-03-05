

<body>
<br><br>
<h2> <center>Geolocation Fraud <br>Detector</center></h2>
<br><br>

</body>

</html>




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
alert('completed');
     return;
  }

 //document.getElementById("timer_sec").innerHTML=count + " seconds"; 

$('#timer_sec').html( count + " seconds.");
}



});




</script>
<span style='font-size:20px;color:#800000' id="timer_sec">x</span>


<div class='col-sm-12'>

        <div class="container">

            <?php


$a = '{"allowed_values":["transcribed"],"current_value":"in_progress","type":"https://www.rev.ai/api/v1/errors/invalid-job-state",
"title":"Job is in invalid state","status":409,"detail":"Job is in invalid state to obtain the output","extensions":{}


';

if (preg_match('/\bin_progressv\b/', $a)) {
    echo 'true';

echo "found";
}


exit();

$jobid ='d3b1807b-3183-466b-b6e6-5d1f581c7553';
include('data6rst.php');



			
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

                  
 <td style='background:purple;color:white;padding:8px;border:none;border-radius:15%;'><?php echo $words; ?></td> 
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


while($row = $res2->fetch()){

                $id= $row['id'];
                $words = $row['words'];
                $entity = $row['entity'];
                $entity_desc = $row['entity_desc'];

echo "<span style='background:purple;color:white;padding:8px;border:none;border-radius:15%;' class='col-sm-12'>$words($entity)</span>";

}

?>







<html>
<script>

var str = "foo/bar/test.html";
var n = str.lastIndexOf('/');
var ext = str.substring(n + 1);

alert(ext);

// add double quotes around the variables
var fileExtention_quotes = ext;
fileExtention_quotes = "'"+fileExtention_quotes+"'";

 var allowedtypes = ["mp3", "MP3", "flac", "FLAC"];
    if(allowedtypes.indexOf(ext) !== -1){
//alert('Good this is a valid content');
}else{
alert("Please Upload a Valid .mp3 or .flac audio File.");
    }


const url = "files/images/gallery/image.jpg";

var ext1 = url.split("/").pop();
alert(ext1);


</script>

<body>
<br><br>
<h2> Call Voice Notes</h1>
<br><br>

</body>

</html>