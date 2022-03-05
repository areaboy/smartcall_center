<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);


if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {


//$username1 = strip_tags($_POST['username']);
//$username = str_replace(' ', '', $username1);




$password = strip_tags($_POST['password']);
$fullname = strip_tags($_POST['fullname']);
$email = strip_tags($_POST['email']);
$mt_id=rand(0000,9999);
$dt2=date("Y-m-d H:i:s");
$ipaddress = strip_tags($_SERVER['REMOTE_ADDR']);

$timer= time();
$ids = $timer.$ipaddress;

$identity = md5($ids);


// honey pot spambots
$emailaddress_pot =$_POST['emailaddress_pot'];
if($emailaddress_pot !=''){
//spamboot detected.
//Redirect the user to google site

echo "<script>
window.setTimeout(function() {
    window.location.href = 'https://google.com';
}, 1000);
</script><br><br>";

exit();
}



if ($fullname == ''){
echo "<div style='background:red;border:none;color:white;padding:10px;' id='alerts_reg'>Fullname is empty</div>";
exit();
}



if ($password == ''){
echo "<div style='background:red;border:none;color:white;padding:10px;' id='alerts_reg'>password is empty</div>";
exit();
}

if ($password == ''){
echo "<div style='background:red;border:none;color:white;padding:10px;' id='alerts_reg'>Password Type is empty</div>";
exit();
}

if ($email == ''){
echo "<div style='background:red;border:none;color:white;padding:10px;'' id='alerts_reg'>Email Address is empty</div>";
exit();
}

$em= filter_var($email, FILTER_VALIDATE_EMAIL);
if (!$em){
echo "<div style='background:red;border:none;color:white;padding:10px;' id='alerts_reg'>Email Address is Invalid</div>";
exit();
}

$ip= filter_var($ipaddress, FILTER_VALIDATE_IP);
if (!$ip){
echo "<div style='background:red;border:none;color:white;padding:10px;' id='alerts_reg'>IP Address is Invalid</div>";
exit();
}





//include database
include('data6rst.php');

// check if email already exist.
$result1 = $db->prepare('SELECT * FROM users where email = :email');
$result1->execute(array(':email' => $email));
$nosofrows1 = $result1->rowCount();
//if ($nosofrows1 == 1)
//if ($nosofrows1 != 0)
if ($nosofrows1 > 0)
{
echo "<br><div style='background:red;border:none;color:white;padding:10px;''  id='alertdata_uploadfiles'>This Email is already taken</div>";
exit();
}


//hash password before sending it to database...
$options = array("cost"=>4);
$hashpass = password_hash($password,PASSWORD_BCRYPT,$options);


//insert into database

$timerx =time();
include("time/now.fn");
$created_time=strip_tags($now);


$statement = $db->prepare('INSERT INTO users
(password,fullname,email,created_time,owner_identity)
 
                          values
(:password,:fullname,:email,:created_time,:owner_identity)');

$statement->execute(array( 

':password' => $hashpass,
':fullname' => $fullname,
':email' => $email,		
':created_time' =>$timer,
':owner_identity' => $identity

));


$stmt = $db->query("SELECT LAST_INSERT_ID()");
$lastId = $stmt->fetchColumn();


$statement1 = $db->prepare('INSERT INTO settings
(modzy_apikey,user_id,created_time,owner_identity,revai_apikey,site_url)
 
                          values
(:modzy_apikey,:user_id,:created_time,:owner_identity,:revai_apikey,:site_url)');

$statement1->execute(array( 

':modzy_apikey' => '',
':user_id' => $lastId,
':created_time' => $timer,		
':owner_identity' => $identity,
':revai_apikey' => '',
':site_url' => 'https://fredjarsoft.com/modzy_callnotes',
));




if($statement1){
echo "<div id='alertdata_uploadfiles_o' class='well alerts alert-success'>Data Created Successfully.
.Redirecting in a second to Login Section.....<img src='loader.gif'><br></div>";


echo "<script>
window.setTimeout(function() {
    window.location.href = 'login.html';
}, 1000);
</script><br><br>";


}

                
else {
echo "<div id='alertdata_uploadfiles' class='alerts alert-danger'>Your Data cannot be submitted to database.<br></div>";
                }   




}



?>



