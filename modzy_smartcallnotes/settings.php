<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include('data6rst.php');


$res = $db->prepare('SELECT * FROM settings where user_id = :user_id');
$res->execute(array(':user_id' => $uid));
$nosofrows1v = $res->rowCount();
$rowsv = $res->fetch();


$modzy_apikey = $rowsv['modzy_apikey'];
$revai_apikey = $rowsv['revai_apikey'];
$site_url = $rowsv['site_url'];



?>