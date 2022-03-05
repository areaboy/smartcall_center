<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



$title = strip_tags($_POST['title']);
$messages = strip_tags($_POST['messages']);
$postid = strip_tags($_POST['postid']);


$pid= strip_tags($_POST['pidx']);
$pidentity= strip_tags($_POST['pidentityx']);
         

include('data6rst.php');

$stmt = $db->prepare('UPDATE audio_calls set audio_title=:audio_title, messages=:messages where id = :id');
$stmt->execute(array(':audio_title' => $title, ':messages' => $messages, ':id' => $postid));

if($stmt){
echo "<div style='background:green;color:white;padding:8px;border:none;'>Contents Updated Successful. Redirecting....</div><br><br>";

echo "<script>
window.setTimeout(function() {
    window.location.href = 'dashboard_query.html?id=$pid&identity=$pidentity';
}, 1000);
</script><br><br>";

}

else{
echo "<div style='background:red;color:white;padding:8px;border:none;'>Data Updated Failed</div><br><br>";
}


?>