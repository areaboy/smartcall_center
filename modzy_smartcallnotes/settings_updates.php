<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



$revai_apikey = strip_tags($_POST['revai_apikey']);
$modzy_apikey = strip_tags($_POST['modzy_apikey']);
echo $site_url = strip_tags($_POST['site_url']);
$userid = strip_tags($_POST['useridx']);


         

include('data6rst.php');

$stmt = $db->prepare('UPDATE settings set revai_apikey=:revai_apikey, modzy_apikey=:modzy_apikey, site_url=:site_url where user_id = :user_id');
$stmt->execute(array(':revai_apikey' => $revai_apikey, ':modzy_apikey' => $modzy_apikey, ':site_url' => $site_url, ':user_id' => $userid));

if($stmt){
echo "<div style='background:green;color:white;padding:8px;border:none;'>Settings Updated Successful. Redirecting....</div><br><br>";

echo "<script>
window.setTimeout(function() {
    window.location.href = 'dashboard.html';
}, 1000);
</script><br><br>";

}

else{
echo "<div style='background:red;color:white;padding:8px;border:none;'>Settings Updated Failed</div><br><br>";
}


?>