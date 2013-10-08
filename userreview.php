<?php
$con=mysql_connect("localhost","anytv_ron","09213972063");
if (mysqli_connect_errno($con))
{echo "Failed to connect to MySQL: " . mysqli_connect_error();}
mysql_select_db("anytv_gbase") or die(mysql_error());
if(isset($_POST['userreviewbtn']))
{
$rating				= $_POST['rating'];
$userratingdesc 	= $_POST['userratingdesc'];
$userratinghidden   = $_POST['userratinghidden'];
$timehidden			= $_POST['timehidden'];
$emailhidden		= $_POST['emailhidden'];
$result = mysql_query("INSERT INTO `guserrating`(`rating`, `rating_description`, `userrating_hidden`, `time_hidden`, `email_hidden`) VALUES ('".$rating."', '".$userratingdesc."', '".$userratinghidden."', '".$timehidden."', '".$emailhidden."')");
if($result==true)
{header('Location:'.$userratinghidden.'#playNow');}
else
{header('Location:'.$userratinghidden.'#playNow');}}
?>