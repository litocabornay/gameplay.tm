<?php
$con=mysql_connect("localhost","anytv_ron","09213972063");

// Check connection
if (mysqli_connect_errno($con))
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

mysql_select_db("anytv_gbase") or die(mysql_error());

	if(isset($_POST['gamebtn']))
	{
	$concept			= $_POST['concept'];
	$graphics			= $_POST['graphics'];
	$sound				= $_POST['sound'];
	$controls			= $_POST['controls'];
	$community			= $_POST['community'];
	$rating				= $_POST['scorehidden'];
	$hidden				= $_POST['scorehiddenpreview'];
	

	
	$result = mysql_query("INSERT INTO `grating`(`concept`, `graphics`, `sound`, `controls`, `community`, `rating`, `hidden_preview`) VALUES ('".$concept."', '".$graphics."', '".$sound."', '".$controls."', '".$community."', '".$rating."', '".$hidden."')");
	
	
	//$row = mysql_fetch_assoc($show);
	
		if($result==true)
		{
			header('Location:game.php?preview='.$hidden.'#playNow');
		}
		else
		{
			header('Location:game.php?preview='.$hidden.'#playNow');
			echo "Something went wrong. Don't ask me.";
		}

		
	}	

	
?>

