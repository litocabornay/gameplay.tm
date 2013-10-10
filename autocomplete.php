<?php
error_reporting(0);
 $q=$_GET['q'];
 $my_data=mysql_real_escape_string($q);
 $mysqli=mysqli_connect('localhost','anytv_ron','09213972063','anytv_gbase') or die("Database Error");
 $sql="SELECT game_name, game_alias, game_publisher FROM gen_info WHERE game_name LIKE '$my_data%' ORDER BY game_name";
 $result = mysqli_query($mysqli,$sql) or die(mysqli_error());

 if($result)
 {
  while($row=mysqli_fetch_array($result))
  {
   
   echo $row['game_name']." (".$row['game_alias'].") (".$row['game_publisher'].")\n";
  }
 }
 
?>

