<?php
$con=mysql_connect("localhost","anytv_ron","09213972063");

// Check connection
if (mysqli_connect_errno($con))
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

mysql_select_db("anytv_gbase") or die(mysql_error());

	if(isset($_POST['videobtn']))
	{
	$videos			= $_POST['vidtext'];
	$vidhidden		= $_POST['vidinvisible'];
	$timehidden		= $_POST['timehidden'];
	$emailhidden	= $_POST['emailhidden'];
	
	$result = mysql_query("INSERT INTO `gvideos`(`game_videos`, `preview_hidden`, `time_hidden`, `email_hidden`) VALUES ('".$videos."', '".$vidhidden."', '".$timehidden."', '".$emailhidden."')");
	
		if($result==true)
		{
			header('Location:'.$vidhidden.'#playNow');
		}
		else
		{
			header('Location:'.$vidhidden.'#playNow');
			echo "Something went wrong. Don't ask me.";
		}

		
	}	

	
?>
