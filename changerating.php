<?php
$con=mysql_connect("localhost","anytv_ron","09213972063");
if (mysqli_connect_errno($con))
{echo "Failed to connect to MySQL: " . mysqli_connect_error();}
mysql_select_db("anytv_gbase") or die(mysql_error());
if(isset($_POST['changeratingbtn']))
{
$changeratingselect		= $_POST['changeratingselect'];
$changeratingtext		= $_POST['changeratingtext'];
$timehidden				= $_POST['timehidden'];
$changeratingid			= $_POST['changeratingid'];
$changeratinghidden		= $_POST['changeratinghidden'];
$result = mysql_query("UPDATE `guserrating` SET `rating`='".$changeratingselect."', `rating_description`='".$changeratingtext."', `time_hidden`='".$timehidden."' WHERE `id`='".$changeratingid."'");
if($result==true)
{header('Location:'.$changeratinghidden.'#playNow');}
else
{header('Location:'.$changeratinghidden.'#playNow');}}
?>

