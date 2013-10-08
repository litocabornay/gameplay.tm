<?php
$con=mysql_connect("localhost","anytv_ron","09213972063");

// Check connection
if (mysqli_connect_errno($con))
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

mysql_select_db("anytv_gbase") or die(mysql_error());

	if(isset($_POST['detailsbtn']))
	{
	$subtitles			= $_POST['subtitles'];
	$help				= $_POST['help'];
	$topup				= $_POST['topup'];
	$latestnews			= $_POST['latestnews'];
	$latestguides		= $_POST['latestguides'];
	$shortdescription	= $_POST['shortdescription'];
	$detailshidden		= $_POST['detailshidden'];
	
	$result = mysql_query("INSERT INTO `additional_info`(`subtitles`, `help`, `topup`, `news`, `guides`, `description`, `hidden`) VALUES ('".$subtitles."', '".$help."', '".$topup."', '".$latestnews."', '".$latestguides."', '".$shortdescription."', '".$detailshidden."')");
	
	
	//$row = mysql_fetch_assoc($show);
	
		if($result==true)
		{
			header('Location:'.$detailshidden.'#od');
		}
		else
		{
			header('Location:'.$detailshidden.'#od');
			echo "Something went wrong. Don't ask me.";
		}

		
	}	

	
?>

