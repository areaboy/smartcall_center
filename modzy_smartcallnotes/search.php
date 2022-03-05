
<?php

error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);



?>

<script>
$(document).on('click', function(e) {
    var div_content = $(".containeryyy");
    if (!$(e.target).closest(div_content).length) {
        div_content.hide();
    }
});


/*
or via javascript

document.addEventListener('mouseup', function(e) {
    var div_content = document.getElementById('containerxxx');
    if (!div_content.contains(e.target)) {
        div_content.style.display = 'none';
    }
});
*/
</script>





<?php
//error_reporting(0);
include('data6rst.php');
if($_POST)
{

$search=strip_tags($_POST['search_data_m']);
$ss=strip_tags($_POST['ssy']);

$uid=strip_tags($_POST['uid']);

// characters capable of causing sql injection
/*
single quote(')
double quote(")
underscore(_)
percent(%)
backslash(\)
*/
if($search == ''){

echo "<div id='alerts_search' class='alerts alert-danger'>Searched Text cannot be empty...</div>";
exit();

}

//check presence of any of this evil characters before passing to prepared statement
$single = substr_count($search,"'");
if($single >0){
echo "<div id='alerts_search' class='alerts alert-danger'>Single Attack Detected...</div>";
exit();
}

$double = substr_count($search,'"');
if($double >0){
echo "<div id='alerts_search' class='alerts alert-danger'>Double Attack Detected...</div>";
exit();
}

/*
$underscore = substr_count($search,"_");
if($underscore >0){
echo "<div id='alerts_search' class='alerts alert-danger'>underscore Attack Detected...</div>";
exit();
}
*/


$percent = substr_count($search,"%");
if($percent >0){
echo "<div id='alerts_search' class='alerts alert-danger'>Percent Attack Detected...</div>";
exit();
}


$backslash = substr_count($search,"\\");
if($backslash >0){
echo "<div id='alerts_search' class='alerts alert-danger'>backslash Attack Detected...</div>";
exit();
}

//echo "<br><br><div class='search_hide_btn1 btn btn-sm btn-warning'>close Search</div>";


echo "<br><br>";
$result = $db->prepare("SELECT * FROM audio_calls where audio_title like :audio_title OR audio_name like :audio_name ");
$result->execute(array(
':audio_title' => '%'.$search.'%',
':audio_name' => '%'.$search.'%'
));


$count = $result->rowCount();




if (strlen($search)< 2) {
    //echo "less than 2";
echo "<div class='searching_res_p search_hide'>Enter Data to Search More<br>

<span class='search_hide_btn1 btn btn-sm btn-warning pull-right'>close</span>
</div>";
}


elseif ($count > 0)
{

 // while starts here
while ($row = $result->fetch()) 
    {
$id = htmlentities(htmlentities($row['id'], ENT_QUOTES, "UTF-8"));

$userid = htmlentities(htmlentities($row['user_id'], ENT_QUOTES, "UTF-8"));

$title = htmlentities(htmlentities($row['audio_title'], ENT_QUOTES, "UTF-8"));
$identity = htmlentities(htmlentities($row['identity'], ENT_QUOTES, "UTF-8"));
$name = htmlentities(htmlentities($row['audio_name'], ENT_QUOTES, "UTF-8"));
$timer1 = htmlentities(htmlentities($row['created_time'], ENT_QUOTES, "UTF-8"));

if($uid ==$userid){
        echo "
<div class='searching_res_p containeryyy' style='cursor:pointer;'>

<a title='Searched Data' href='dashboard_query.html?id=$id&identity=$identity'>
<span style='font-size:14px; color:white'>Audio Title: $title</span><br>
<br>
<span style='font-size:14px; color:white'>Audion Name: $name</span><br>
<span style='color:pink;font-size:12px'><span  data-livestamp='$timer1' ></span> </span><br>


<span style='display:none' class='search_hide_btn1 btn btn-sm btn-warning pull-right'>close</span>
</a>
</div>";

    }  
}     

// while ends here


}else{

echo "<div  id='alerts_search' class='alerts alert-danger searching_res_p1 search_hide'>Searched Content not Found
<span style='display:none' class='search_hide_btn1 btn btn-sm btn-warning pull-right'>close</span>
</div>";

}





}
?>














