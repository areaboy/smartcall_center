<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 

$email_s = strip_tags($_POST['email']);

?>

        




<?php

 

$email = strip_tags($_POST['email']);
$password = strip_tags($_POST['password']);


if ($email == ''){
echo "<div class='alert alert-danger' id='alerts_login'><font color=red>Email is empty</font></div>";
exit();
}


if ($password == ''){
echo "<div class='alert alert-danger' id='alerts_login'><font color=red>password is empty</font></div>";
exit();
}


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





include('data6rst.php');
$result = $db->prepare('SELECT * FROM users where email = :email');

		$result->execute(array(
			':email' => $email

    ));

$count = $result->rowCount();

$row = $result->fetch();

if( $count == 1 ) {


//start hashed passwordless Security verify
if(password_verify($password,$row["password"])){
            //echo "Password verified and ok";


$userid = htmlentities(htmlentities($row["id"]));
$fullname = htmlentities(htmlentities($row["fullname"]));
$email = htmlentities(htmlentities($row["email"]));
$owner_identity = htmlentities(htmlentities($row["owner_identity"]));



// initialize session if things where ok via html5 local storage.
echo "<script>
sessionStorage.setItem('useridcall', '$userid');
sessionStorage.setItem('fullnamecall', '$fullname');
sessionStorage.setItem('emailcall', '$email');
sessionStorage.setItem('owneridcall', '$owner_identity');

</script>";





echo "<div class='alert alert-success'>Login sucessful <img src='ajax-loader.gif'></div>";
echo "<script>window.location='dashboard.html'</script>";




}
else{
echo "<br><br><div style='background:red;color:white;padding:10px;border:none;' id='alerts_login'>Password Does not Matched</div>";

}



}
else {
echo "<br><br><div style='background:red;color:white;padding:10px;border:none;' id='alerts_login'>User with This Email does not exist..</div>";
}






?>

<?php ob_end_flush(); ?>
