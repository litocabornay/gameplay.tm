<?php
$con=mysql_connect("localhost","anytv_ron","09213972063");
if (mysqli_connect_errno($con))
{echo "Failed to connect to MySQL: " . mysqli_connect_error();}
mysql_select_db("anytv_gbase") or die(mysql_error());
if(isset($_POST['reviewbtn']))
{
$reviewarea		= $_POST['review'];
$reviewhidden	= $_POST['invisible'];
$timehidden		= $_POST['timehidden'];
$emailhidden	= $_POST['emailhidden'];
$result = mysql_query("INSERT INTO `greview`(`game_reviews`, `preview_hidden`, `time_hidden`, `email_hidden`) VALUES ('".$reviewarea."', '".$reviewhidden."', '".$timehidden."', '".$emailhidden."')");
if($result==true)
{header('Location:'.$reviewhidden.'#ur');}
else
{header('Location:'.$reviewhidden.'#ur');}}		
?>
