<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);




include('data6rst.php');

$queryid = $_POST['queryid'];
$page_row_call = $_POST['page_row_call'];
$user_id= strip_tags($_POST['user_id']);




$res= $db->prepare("SELECT count(*) as totalcount FROM audio_calls where user_id=:user_id order by id");
$res->execute(array(':user_id' =>$user_id));
$t_row = $res->fetch();
$totalcount = $t_row['totalcount'];

if($totalcount == 0){
//echo "<div style='background:red;color:white;padding:10px;border:none;'>Data has been Uploaded by you Yet.. <b></b></div>";
echo 11;
exit();
}

$result = $db->prepare("SELECT * FROM audio_calls where user_id=:user_id order by id DESC limit :row1, :rowpage");
$result->bindValue(':rowpage', (int) trim($page_row_call), PDO::PARAM_INT);
$result->bindValue(':row1', (int) trim($queryid), PDO::PARAM_INT);
$result->bindValue(':user_id', trim($user_id), PDO::PARAM_STR);
//$result->bindValue(':project_id', trim($projectid), PDO::PARAM_INT);
$result->execute();

$count_post = $result->rowCount();


$result_arr = array();
$result_arr[] = array("allcount" => $totalcount);
while($row = $result->fetch()){
	
$id = htmlentities(htmlentities($row['id'], ENT_QUOTES, "UTF-8"));
$title = htmlentities(htmlentities($row['audio_title'], ENT_QUOTES, "UTF-8"));
$tname = htmlentities(htmlentities($row['audio_name'], ENT_QUOTES, "UTF-8"));
$messages = $row['messages'];
$timer = htmlentities(htmlentities($row['created_time'], ENT_QUOTES, "UTF-8"));
$userid = htmlentities(htmlentities($row['user_id'], ENT_QUOTES, "UTF-8"));
$ownerid = htmlentities(htmlentities($row['owner_identity'], ENT_QUOTES, "UTF-8"));
$status = htmlentities(htmlentities($row['status'], ENT_QUOTES, "UTF-8"));
$job_id = htmlentities(htmlentities($row['job_id'], ENT_QUOTES, "UTF-8"));
$identity = htmlentities(htmlentities($row['identity'], ENT_QUOTES, "UTF-8"));

$messages1 = substr($messages, 0, 200)."...";  

$result_arr[] = array(
"id" => $id,
"title" => $title,
"tname" => $tname,
"messages" => $messages,
"timer" => $timer,
"userid" => $userid,
"ownerid" => $ownerid,
"status" => $status,
"job_id" => $job_id,
"identity" => $identity,
"messages1" => $messages1
);


}
echo json_encode($result_arr);