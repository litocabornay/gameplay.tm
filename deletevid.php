<?php

$con=mysql_connect("localhost","anytv_ron","09213972063");
if (mysqli_connect_errno($con))
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
mysql_select_db("anytv_gbase") or die(mysql_error());
			
	if(isset($_POST['deletevidbtn']))
	{
	
	$vidid = $_POST['idhidden'];
	$preview_hidden	= $_POST['previewhidden'];

	
	$result = mysql_query('DELETE FROM gvideos WHERE id="'.$vidid.'"');

		if($result==true)
		{
			header('Location:'.$preview_hidden.'#playNow');
		}
		else
		{
			header('Location:'.$preview_hidden.'#playNow');
			echo "Something went wrong. Don't ask me.";
		}

		
	}	
	
	
?>
