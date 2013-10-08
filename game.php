<?php


require '1/setup.php';


// Create a new Google API client
$client = new apiClient();
//$client->setApplicationName("Tutorialzine");

// Configure it
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setDeveloperKey($api_key);
$client->setRedirectUri($redirect_url);
$client->setApprovalPrompt('auto');
$oauth2 = new apiOauth2Service($client);


// The code parameter signifies that this is
// a redirect from google, bearing a temporary code
if (isset($_GET['code'])) {
	
	// This method will obtain the actual access token from Google,
	// so we can request user info
	$client->authenticate();
	
	// Get the user data
	$info = $oauth2->userinfo->get();
	
	// Find this person in the database
	$person = ORM::for_table('glogin_users')->where('email', $info['email'])->find_one();
	
	if(!$person){
		// No such person was found. Register!
		
		$person = ORM::for_table('glogin_users')->create();
		
		// Set the properties that are to be inserted in the db
		$person->email = $info['email'];
		$person->name = $info['name'];
		
		if(isset($info['picture'])){
			// If the user has set a public google account photo
			$person->photo = $info['picture'];
		}
		else{
			// otherwise use the default
			$person->photo = 'assets/img/default_avatar.jpg';
		}
		
		// insert the record to the database
		$person->save();
	}
	
	// Save the user id to the session
	$_SESSION['user_id'] = $person->id();
	
	// Redirect to the base demo URL
	header("Location: $redirect_url");
	exit;
}

// Handle logout
if (isset($_GET['logout'])) {
	unset($_SESSION['user_id']);
	
}

$person = null;
if(isset($_SESSION['user_id'])){
	// Fetch the person from the database
	$person = ORM::for_table('glogin_users')->find_one($_SESSION['user_id']);
}

	$con=mysql_connect("localhost","anytv_ron","09213972063");
	if (mysqli_connect_errno($con))
	{echo "Failed to connect to MySQL: " . mysqli_connect_error();}	
	mysql_select_db("anytv_gbase") or die(mysql_error());
	
	$now = new DateTime();
	$date_time = $now->format('Y-m-d H:i:s');
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<base href="<?php echo $myroot; ?>">
	<link rel="icon" type="image/png" href="favicon.ico" style="width:16px; height:16px;"/>
	<title>gameplay.tm | <?php echo $get_preview; ?></title>
	<meta content="http://www.gameplay.tm" property="og:url">
	<meta content="g/gameplay-circ-black.png" property="og:image">
	<meta content="Gameplay.tm" property="og:title">
	<meta content="Welcome to any.TV Gameplay, where you can watch the gameplay of your favorite games, add videos, comment and gain achievements as well! What are you waiting for? Watch. Play. Conquer." property="og:description">

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="bootstrap.min.css?09302013" rel="stylesheet" media="screen"/>
	<link href="externals.css?1082013" rel="stylesheet" media="screen" type="text/css"/>
	<link href="css/examples.css?09302013" rel="stylesheet" type="text/css"/>
	<link href="lightbox/css/lightbox.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />
	
	<!-- Force latest IE rendering engine or ChromeFrame if installed -->
	<!--[if IE]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<![endif]-->

	
	
	<style>
		.modal-title
		{
			font-family:'outagecut';
		}
		
		.gameBadge
		{
			position:relative;
			margin-left:40px;
			margin-top:390px;
			height:170px;
			width:170px;
			border-radius:100px 100px 100px 100px;
			border:20px solid #060606;
		}
	
		.lb-container
		{
			width:50%;
		}
		
			
		.ac_results
		{
			border:1px solid #888;	
		}
		
		#screenies:hover
		{
			border-radius:0 30px 0 30px;
			-webkit-box-shadow: -2px 0px 20px rgba(255, 255, 255, 0.7);
			-moz-box-shadow:    -2px 0px 20px rgba(255, 255, 255, 0.7);
			box-shadow:         -2px 0px 20px rgba(255, 255, 255, 0.7);
		}
		
		#playNow:hover
		{
			border-radius:0 100px 0 100px;
			-webkit-box-shadow: -2px 0px 20px rgba(255, 255, 255, 0.7);
			-moz-box-shadow:    -2px 0px 20px rgba(255, 255, 255, 0.7);
			box-shadow:         -2px 0px 20px rgba(255, 255, 255, 0.7);
		}
		
		#playNow
		{
			border-radius:100px 0 100px 0;
		}
	</style>
	
	<script src="http://code.jquery.com/jquery.js?09302013" type="text/javascript"></script>
	<script src="asd/js/jquery.min.js?09302013" type="text/javascript"></script>
	<script type="text/javascript" language="javascript" src="jquery-ui-1.10.3/ui/jquery-ui.js?09302013"></script>
		<script type="text/javascript" src="js/jquery.autocomplete.js?09302013"></script>
		<script>
			 $(document).ready(function()
			 {
				  $("#search").autocomplete("autocomplete.php", {
				  });
			 });
		</script>
		<script>
			function searchClick()
			{
				var srchVal = $("#search").val();
				var valArr = srchVal.split("(");
				console.log(valArr);
				window.location = ""+valArr[1].replace(/\)/g,"");
			}
		</script>
	<script src="lightbox/js/lightbox-2.6.min.js"></script>
	
</head>
<body style="font-family:'outagecut';">


<nav class="navbar navbar-default navbar-fixed-top" role="navigation"> 

  <div class="collapse navbar-collapse navbar-ex1-collapse">
  
  <button type="button" id="mainHomeButtonLogo" class="navbar-brand btn btn-default dropdown-toggle" data-toggle="dropdown"><img src="images/gameplay-tm-logo.png" style="height:35px; margin-top:-8px; margin-bottom:-5px;"/></button>
  
  <ul class="dropdown-menu" id="imageMenu" role="menu">
  
   <li><a href="/"><img src="favicon.ico" style="width:16px; height:16px;"> &nbsp<b>Home</b></a></li>
   <li><a href="/forums"><img src="favicon.ico" style="width:16px; height:16px;"> &nbsp<b>Forums</b></a></li>
   <li><a href="http://www.any.tv/staff/"><img src="favicon.ico" style="width:16px; height:16px;"> &nbsp<b>Contribute</b></a></li>
   <li><a href="http://www.any.tv/blog/what-is-any-tv/"><img src="favicon.ico" style="width:16px; height:16px;"> &nbsp<b>About us</b></a></li>
   
  <li id="divider" class="divider"></li>
	<p style="color:black;"><b>S O C I A L</b></p>
  
   <li><a href="https://www.facebook.com/anyTVnetwork" target="_blank"><img src="favicons/fb.png"> &nbsp<b>Facebook</b></a></li>
   <li><a href="https://plus.google.com/109971475987405213729/posts" target="_blank"><img src="favicons/gplus.png"> &nbsp<b>Google+</b></a></li>
   <li><a href="http://www.twitch.tv/team/anyTVnetwork" target="_blank"><img src="favicons/twitch.png"> &nbsp<b>Twitch</b></a></li>
   <li><a href="https://twitter.com/anyTVnetwork" target="_blank"><img src="favicons/twitter.png"> &nbsp<b>Twitter</b></a></li>
   <li><a href="http://www.youtube.com/anyTVnetwork" target="_blank"><img src="favicons/youtube.png"> &nbsp<b>Youtube</b></a></li>
   
  <li id="divider" class="divider"></li>
	<p style="color:black;"><b>R E C O M M E N D E D &nbsp S I T E S</b></p>
	
    <li><a href="http://www.any.tv" target="_blank"><img src="favicons/png.png"> &nbsp<b>any.TV</b></a></li>
	<li><a href="http://www.community.tm/" target="_blank"><img src="favicons/communityTM.png"> &nbsp<b>Community.tm</b></a></li>
    <li><a href="http://www.dashboard.tm/" target="_blank"><img src="favicons/dashboardTM.png"> &nbsp<b>Dashboard.tm</b></a></li>
	<li><a href="http://www.games.tm" target="_blank"><img src="favicons/tv.png"> &nbsp<b>Games.tm</b></a></li>
    <li><a href="http://www.heartbeat.tm" target="_blank"><img src="favicons/tv.png"> &nbsp<b>Heartbeat.tm</b></a></li> 
    <li><a href="http://www.mmo.tm/" target="_blank"><img src="favicons/mmoTM.png"> &nbsp<b>MMO.tm</b></a></li>
    <li><a href="http://www.videobar.tm" target="_blank"><img src="favicons/tv.png"> &nbsp<b>Videobar.tm</b></a></li>
    
  </ul>
  
  
  
  
  
      <span class="form-inline" style="margin-left:30px;">
		  <div class="col-lg-6" style="margin-top:6px;">
			<div class="input-group">
			
			 <span id="form" class="input-group-btn form-inline">
			  <input type="text" id="search" style="border:none; background-color:#060606;" onkeypress="searchClick()" class="form-control" placeholder="Find your game here!"></input>

			 
				<button class="btn btn-default" type="button" style="border-radius:0 4px 4px 0;"><span class="glyphicon glyphicon-search" style="padding-bottom:6px" onclick="searchClick()"/></button>
			  </span>
			  
			</div><!-- /input-group -->
		  </div><!-- /.col-lg-6 -->
 
    <ul class="nav navbar-nav navbar-right">
      <li>
		<div class="btn-group" id="userButtonArea">
		
		<?php if($person):?>
		
		  <button type="button" id="buttonUser" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
			<b style="font-family:Tahoma, Geneva, sans-serif; font-size:15px;"><?php echo htmlspecialchars($person->email)?> 

			<?php 
					$result = mysql_query("SELECT email FROM glogin_users WHERE email LIKE '%@any.tv%'");
					while ($row = mysql_fetch_assoc($result))
					{
					if($person->email == $row['email'])
					echo '<i>admin</i>';
					}
			?>
					
			<?php 
					$result = mysql_query("SELECT email FROM glogin_users WHERE email LIKE '%@gmail.com%'");
					while ($row = mysql_fetch_assoc($result))
					{
					if($person->email == $row['email'])
					echo '<i>guest</i>';
					}
			?> &nbsp </b><span class="caret"></span>
			</button>
		  
		  <ul class="dropdown-menu dropdown-warning" id="userDropDown" role="menu">
		  
			
			<?php
				$result2 = mysql_query("SELECT game_name, game_alias from gen_info WHERE game_alias ='".$get_preview."'");
				$result = mysql_query("SELECT news, guides from additional_info WHERE hidden ='".$get_preview."'");
				$row = mysql_fetch_assoc($result);
				$row2 = mysql_fetch_assoc($result2);
				
				echo	'<li><a href="'.$row['news'].'"><span class="glyphicon glyphicon-globe"></span> &nbsp<b>Latest News for '.$row2['game_name'].'</b></a></li>';
				echo	'<li><a href="'.$row['guides'].'"><span class="glyphicon glyphicon-info-sign"></span> &nbsp<b>Latest Guides for '.$row2['game_name'].'</b></a></li>';
		
			?>
			
			
			<li class="divider"></li>
			<li><a href="profile"><span class="glyphicon glyphicon-tower"></span> &nbsp<b>My Achievements</b></a></li>
			<li class="divider"></li>
			<li><a href="logout.php" name="logout" class="logoutButton"><span class="glyphicon glyphicon-log-out"></span> &nbsp<b>Sign-out</b></a></li>
		  </ul>
		  
		  
			<?php elseif($person == ""):?>
			
				<a href="<?php echo $client->createAuthUrl()?>" class="googleLoginButton" style="margin-top:-28px;"><img src="siwg2.png" id="loginImage" ></img></a>
			
			<?php endif;?>   
		</div>	
	  </li>
    </ul>
  </div><!-- /.navbar-collapse -->
  
  <div class="container">
  
  
	
  </div>
</nav>


	<?php
	$result = mysql_query("SELECT images FROM gimages WHERE images LIKE '".$get_preview."-cover%'");
	$row = mysql_fetch_assoc($result);
	$result2 = mysql_query("SELECT game_name FROM gen_info WHERE game_alias = '".$get_preview."'");
	$row2 = mysql_fetch_assoc($result2);
	$result3 = mysql_query("SELECT description FROM additional_info WHERE hidden = '".$get_preview."'");
	$row3 = mysql_fetch_assoc($result3);

	$result4 = mysql_query("SELECT game_alias FROM gen_info WHERE game_alias = '".$get_preview."'");
	$row4 = mysql_fetch_assoc($result4);
	
	$result5 = mysql_query("SELECT game_genre FROM gen_info WHERE game_alias = '".$get_preview."'");
	$row5 = mysql_fetch_assoc($result5);

		
			echo '<div id="gameCoverPhoto" style="background:url(server/php/files/'.$row['images'].');">';
			
				echo '<div id="area">';
					echo '<h1 id="gameName" >'.$row2['game_name'].'</b></h1>';
					echo '<b id="gameDescription">'.$row3['description'].'</b>';
				echo '</div>';
				
				if($row5['game_genre'] == " First-Person Shooter ")
					{
						echo '<img class="gameBadge" src="img/fpsbadge.gif"/>';
					}
				else if($row5['game_genre'] == "Battle Arena ")
					{	
						echo '<img class="gameBadge" src="img/mobabadge.gif"/>';
					}
				else if($row5['game_genre'] == " Role-Playing ")
				
					{
						echo '<img class="gameBadge" src="img/mmorpgbadge.gif"/>';
					}

			echo '</div>';
		
	?>
	





<div class="container">

<div class="row" id="gameRow1">
	<div class="col-md-7" id="middleGameRow1" style="word-wrap:break-word;">

		<?php
		$result = mysql_query("SELECT game_site FROM gen_info WHERE game_alias = '".$get_preview."'");
		$row = mysql_fetch_assoc($result);
		echo	'<span><a href="'.$row['game_site'].'" class="btn btn-lg btn-danger" target="_blank" id="playNow" style="padding:30px; padding-top:50px; padding-bottom:50px; font-size:2.75em; margin-top:58px; margin-bottom:24px;">P l a y &nbsp N o w !</a>';
		?>
	
		<a href="http://www.games.tm" class="btn btn-link btn-sm" style="color:#FDB900; margin-top:40px;">Get your own play now link on <br/>Games.tm!</a></span><br/><br/>
		
			<div id="playVideo" style="padding:0px;">
			
			<?php
			$result = mysql_query("SELECT game_videos, preview_hidden FROM gvideos WHERE preview_hidden = '".$get_preview."'");
			$row = mysql_fetch_assoc($result);	
			if(!($row['game_videos'] == ""))
			{
				echo '<iframe width="650" height="400" src="http://www.youtube.com/embed/'.$row["game_videos"].'" style="text-align:center;">';
				echo '</iframe>';
			}

			?>
		</div><br/>
		
		<form name="input" action ="vid.php" class="form-inline" method="POST" id="tag2" onsubmit="vidSubmit();">
		
		
		<?php
		if(isset($_SESSION['user_id'])){
		echo '<input type="text" id="demo" name="vidtext" class="form-control" data-loading-text="..." placeholder="Add a gameplay video here!" style="margin-bottom:5px; width:547px;" value="" required></input>';
		echo '<input type="submit" name="videobtn" style="margin-top:-2px;" class="btn btn-success" onclick="changeVideo()"/>';
		}
		else
		{
		
		}
		?>
		
		
		<?php
		echo '<input type="text" name="vidinvisible" value="'.$get_preview.'" style="display:none;"></input>'; 
		echo '<input type="text" id="emailhidden" name="emailhidden" value="'.$person->email.'" style="display:none;"/>';
		echo '<input type="text" id="timehidden" name="timehidden" value="'.$date_time.'" style="display:none;"/>';
		?>
		

		</form><hr/>
		
	<?php
	
	$result = mysql_query("SELECT * FROM grating WHERE hidden_preview = '".$get_preview."'");
	$row = mysql_fetch_assoc($result);
	
	
	$result2 = mysql_query("SELECT email from glogin_users WHERE email LIKE '%@any.tv%' AND email = '".$person->email."'");
	$row2 = mysql_fetch_assoc($result2);	
					
	if($row2['email'] !== null)
	{
		if($row['id'] == null)
		{
		echo '<form name="input" action ="score.php" method="POST">
				<select class="span2" id="a" name="concept">
				  <option value="1">1 - Very Bad</option>
				  <option value="2">2 - Bad</option>
				  <option value="3">3 - Average</option>
				  <option value="4">4 - Good</option>
				  <option value="5">5 - Very Good</option>
				</select> &nbsp <strong style="color:#FDB900;">CONCEPT</strong> -- Is the game new, unique, cutting-edge and can offer something different?<br/><br/>
						<select class="span2" id="b" name="graphics">
				  <option value="1">1 - Very Bad</option>
				  <option value="2">2 - Bad</option>
				  <option value="3">3 - Average</option>
				  <option value="4">4 - Good</option>
				  <option value="5">5 - Very Good</option>
				</select> &nbsp <strong style="color:#FDB900">GRAPHICS</strong> -- Is it eye-catching and beautiful and is the visual performance top-notch?<br/><br/>
						<select class="span2" id="c" name="sound">
				  <option value="1">1 - Very Bad</option>
				  <option value="2">2 - Bad</option>
				  <option value="3">3 - Average</option>
				  <option value="4">4 - Good</option>
				  <option value="5">5 - Very Good</option>
				</select> &nbsp <strong style="color:#FDB900">SOUND</strong> -- Will the music, score, voice-acting and sound performance get you more in the game?<br/><br/>
						<select class="span2" id="d" name="controls">
				  <option value="1">1 - Very Bad</option>
				  <option value="2">2 - Bad</option>
				  <option value="3">3 - Average</option>
				  <option value="4">4 - Good</option>
				  <option value="5">5 - Very Good</option>
				</select> &nbsp <strong style="color:#FDB900">CONTROLS</strong> -- Are the controls smooth and easy to handle and does it fight the user?<br/><br/>
						 <select class="span2" id="e" name="community">
				  <option value="1">1 - Very Bad</option>
				  <option value="2">2 - Bad</option>
				  <option value="3">3 - Average</option>
				  <option value="4">4 - Good</option>
				  <option value="5">5 - Very Good</option>
				</select> &nbsp <strong style="color:#FDB900">COMMUNITY</strong> -- Do the people playing and governing the game cultivate the gaming experience?<br/>';
				
		echo '<input type="text" name="scorehiddenpreview" value="'.$get_preview.'" style="display:none;">';
		echo '</input>';
		echo '<br/>';
		echo '<input type="submit" name="gamebtn" class="btn btn-success btn-block" onclick="myFunction()"/>';
		echo '<input type="text" id="elmID" name="scorehidden" value="" style="display:none"/>';
		echo '</form>';
		}
		elseif($row['id'] !== null)
		{
		echo '<div id="staffRatingTable">';
		echo '<table id="staffRatingTable" class="table-striped" cellpadding="10px">';
		echo '<tr>';
		echo '<td class="col-md-7"><small><b style="color:#FDB900">CONCEPT</b> -- Is the game new, unique, cutting-edge and can offer something different?</small></td>';
		echo '<td class="span4"><h4 style="font-size:3em; font-family:outagecut; text-align:center"><strong>'.$row["concept"].'</strong><small> out of 5</small></h4></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td class="col-md-7"><small><b style="color:#FDB900">GRAPHICS</b> -- Is it eye-catching and beautiful and is the visual performance top-notch?</small></td>';
		echo '<td class="span4"><h4 style="font-size:3em; font-family:outagecut; text-align:center"><strong>'.$row["graphics"].'</strong><small> out of 5</small></h4></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td class="col-md-7"><small><b style="color:#FDB900">SOUND</b> -- Will the music, score, voice-acting and sound performance get you more in the game?</small></td>';
		echo '<td class="span4"><h4 style="font-size:3em; font-family:outagecut; text-align:center"><strong>'.$row["sound"].'</strong><small> out of 5</small></h4></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td class="col-md-7"><small><b style="color:#FDB900">CONTROLS</b> -- Are the controls smooth and easy to handle and does it fight the user?</small></td>';
		echo '<td class="span4"><h4 style="font-size:3em; font-family:outagecut; text-align:center"><strong>'.$row["controls"].'</strong><small> out of 5</small></h4></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td class="col-md-7"><small><b style="color:#FDB900">COMMUNITY</b> -- Do the people playing and governing the game cultivate the gaming experience?</small></td>';
		echo '<td class="span4"><h4 style="font-size:3em; font-family:outagecut; text-align:center"><strong>'.$row["community"].'</strong><small> out of 5</small></h4></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td class="span5"><h3 style="font-family:outagecut;"><img src="g/gameplay-logo-3a.png"/> STAFF RATING</h3></td>';
		echo '<td class="span5"><h4 style="font-size:2.25em; color:#FDB900; font-family:outagecut;"><b>'.$row["rating"].'</b></h4></td>';
		echo '</tr>';
		echo '</table>';
		echo '</div>';
		}
	}		
	elseif($row2['email'] == null)
	{
		if($row['id'] == null)
		{
			echo '<p style="text-align:center;">The staff has not rated this game yet! We\'ll try to rate it as soon as possible.</p>';
		}
		elseif($row['id'] !== null)
		{
			echo '<div id="staffRatingTable">';
			echo '<table id="staffRatingTable" class="table-striped" cellpadding="10px">';
			echo '<tr>';
			echo '<td class="col-md-7"><small><b style="color:#FDB900">CONCEPT</b> -- Is the game new, unique, cutting-edge and can offer something different?</small></td>';
			echo '<td class="span4"><h4 style="font-size:3em; font-family:outagecut; text-align:center"><strong>'.$row["concept"].'</strong><small> out of 5</small></h4></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td class="col-md-7"><small><b style="color:#FDB900">GRAPHICS</b> -- Is it eye-catching and beautiful and is the visual performance top-notch?</small></td>';
			echo '<td class="span4"><h4 style="font-size:3em; font-family:outagecut; text-align:center"><strong>'.$row["graphics"].'</strong><small> out of 5</small></h4></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td class="col-md-7"><small><b style="color:#FDB900">SOUND</b> -- Will the music, score, voice-acting and sound performance get you more in the game?</small></td>';
			echo '<td class="span4"><h4 style="font-size:3em; font-family:outagecut; text-align:center"><strong>'.$row["sound"].'</strong><small> out of 5</small></h4></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td class="col-md-7"><small><b style="color:#FDB900">CONTROLS</b> -- Are the controls smooth and easy to handle and does it fight the user?</small></td>';
			echo '<td class="span4"><h4 style="font-size:3em; font-family:outagecut; text-align:center"><strong>'.$row["controls"].'</strong><small> out of 5</small></h4></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td class="col-md-7"><small><b style="color:#FDB900">COMMUNITY</b> -- Do the people playing and governing the game cultivate the gaming experience?</small></td>';
			echo '<td class="span4"><h4 style="font-size:3em; font-family:outagecut; text-align:center"><strong>'.$row["community"].'</strong><small> out of 5</small></h4></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td class="span5"><h3 style="font-family:outagecut;"><img src="g/gameplay-logo-3a.png"/> STAFF RATING</h3></td>';
			echo '<td class="span5"><h4 style="font-size:2.25em; color:#FDB900; font-family:outagecut;"><b>'.$row["rating"].'</b></h4></td>';
			echo '</tr>';
			echo '</table>';
			echo '</div>';
		}
	}	
	?>
	
		<br/><div id="screens" class="panel panel-default">
			<div id="screens" class="panel-heading">
			  <h4 class="panel-title" style="font-family:outagecut;">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
				 <span class="glyphicon glyphicon-picture"></span> Screenshots
				</a>
			  </h4>
			</div>
			<div id="collapseFour" class="panel-collapse collapse in">
			  <div class="panel-body">
			  
				<?php	
				$result = mysql_query("SELECT images FROM gimages WHERE images LIKE '".$get_preview."%-ss%'");
				while ($row = mysql_fetch_assoc($result))
				{
					if($row['images'] !== null)
					{
					echo '<a href="server/php/files/'.$row["images"].'" data-lightbox="Screenshots" title="'.$get_preview.' Screenshots - '.$row["images"].'">';
						echo '<img src="server/php/files/'.$row["images"].'" id="screenies"/>';
					echo '</a>';
					}
					else
					{
					
						//di rin to lumalabas. ausiiiin!
						echo	'No screenies available at the moment';
						//
					}
					
				}
				?>


			  </div>
			</div>
		  </div>

	</div>




	<div class="col-md-5" id="leftGameRow1" style="word-wrap:break-word">
		
	<div style="word-wrap:break-word">
			
	<?php
		$result = mysql_query("SELECT id, rating, rating_description, userrating_hidden FROM guserrating WHERE userrating_hidden = '".$get_preview."'");
		$row = mysql_fetch_assoc($result);
		if($person->email == true && mysql_num_rows($result) == false)

		{
			echo	'<h1 style="font-family:outagecut; color:#FDB900;">Rate Me</h1>';
			echo	'<div class="buttons">
					<button class="blue-pill deactivated rating-enable" style="display:none;">enable</button>
					<button class="blue-pill rating-disable" style="display:none";>disable</button>
					</div>';				
			echo	'<div class="buttons">
						<button class="deactivated rating-enable">Bar Style</button>
						<button class="rating-disable">Normalize</button>
					</div><br/>
					<div class="input select rating-a" required>
					<div class="modal fade" id="confirmScore" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<form name="input" action ="userreview.php" method="POST">
						<div class="modal-dialog">
						  <div class="modal-content">
							<div class="modal-header">
							  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
							  <h4 class="modal-title">Rating Confirmation</h4>
							</div>
							<div class="modal-body">
							  <p>Thanks for taking the time to rank this game! Do you want to confirm your score and post?</p>
							</div>
							<div class="modal-footer">
							  <button type="button" class="btn btn-default" data-dismiss="modal">Nope</button>
							  <button href="userreview.php" name="userreviewbtn" class="btn btn-success">Yes, please</button>
							</div>
						  </div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					
						<select id="example-a" name="rating" required>
							<option value=""></option>
							<option value="1">POISON!</option>
							<option value="2">BROKEN</option>
							<option value="3">BORING</option>
							<option value="4">SO-SO</option>
							<option value="5">NEUTRAL</option>
							<option value="6">INTERESTING</option>
							<option value="7">PRETTY COOL</option>
							<option value="8">GREAT</option>
							<option value="9">AWESOME</option>
							<option value="10">NIRVANA!</option>
						</select>
					</div>
					
					<textarea name ="userratingdesc" id="userratingtext" class="form-control" placeholder="Why that score?" style="margin-bottom:5px; resize:none; width:300px; height:100px;" required></textarea>
					
					<input type="text" name="userratinghidden" value="'.$get_preview.'" style="display:none;"/>
					<input type="text" id="emailhidden" name="emailhidden" value="'.$person->email.'" style="display:none;"/>
					<input type="text" id="timehidden" name="timehidden" value="'.$date_time.'" style="display:none;"/>
					
					<br/>
					<button href="#confirmScore" data-toggle="modal" id="userratingbutton" class="btn btn-success"  data-loading-text="..." style="width:300px;">Submit</button>
					
					</form>';
			
		}

		elseif($person->email == false)
		{	
			echo	'<h3 id="loginFalseRateArea">Sign in to rate this game!</h3>';
		}
		
		
		elseif($person->email == true && mysql_num_rows($result) == true)
		{	
			echo	'My score:';
			echo	'<button href="#confirmChangeScore" data-toggle="modal" class="label pull-right" style="background-color:#FDB900; color:black;">Change Score</button>';
			echo	'<h1 style="font-family:outagecut; font-size:14em; color:#FDB900; margin-top:-30px; text-align:right">'.$row['rating'].'<small>/ 10</small></h1>';
			echo	'<p style="text-align:center; margin-bottom:20px;">"'.$row['rating_description'].'"</p>';
			//echo	$row['id'];
		}	
	?>

	<!----------------------- Modal for Change Score --------------------------------->
	<div class="modal fade" id="confirmChangeScore" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		  <div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			  <h4 class="modal-title">Change rating</h4>
			</div>
			<div class="modal-body">
			<p>I see you are trying change your previous rating of this game. Let me help you with that. Just repick your rating of the game below and you can also change the description. (Which will probably explain why you are changing the score in the first place)</p><br/>
			  <div class="buttons">
						<button class="blue-pill deactivated rating-enable" style="display:none;">enable</button>
						<button class="blue-pill rating-disable" style="display:none";>disable</button>
					</div>
					
					<form name="input" action="changerating.php" id="changeRatingForm" method="POST">
					
						<div class="input select rating-a">
							<select id="example-a" name="changeratingselect" required>
								<option value=""></option>
								<option value="1">POISON!</option>
								<option value="2">BROKEN</option>
								<option value="3">BORING<option>
								<option value="4">SO-SO</option>
								<option value="5">NEUTRAL</option>
								<option value="6">INTERESTING</option>
								<option value="7">PRETTY COOL</option>
								<option value="8">GREAT</option>
								<option value="9">AWESOME</option>
								<option value="10">NIRVANA!</option>
							</select>
						</div>
						<textarea id="changeratingtext" name="changeratingtext" class="form-control" style="resize:none; width:515px; height:150px; margin-bottom:30px;" placeholder="Post your replacement reason here. Or you could leave it blank if you want to." required></textarea>
						
						<?php 
						$result = mysql_query("SELECT id FROM guserrating WHERE userrating_hidden = '".$get_preview."'");
						$row = mysql_fetch_assoc($result);
						echo	'<input type="text" name="changeratingid" value="'.$row['id'].'" style="display:none;">';
						echo	'<input type="text" name="changeratinghidden" value="'.$get_preview.'" style="display:none;">';
						echo	'<input type="text" id="timehidden" name="timehidden" value="'.$date_time.'" style="display:none;"/>';
						//echo	$row['id'];
						?>
						
							<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
							
							<input type="submit" name="changeratingbtn"  data-loading-text="Loading..." class="btn btn-success"/>
					</form>
				</div>
			</div>
		  </div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	  </div><!-- /.modal -->
	 <!---------------------------------------------------------------------------------->
	

	
		
		<div id="vidselectorcontainer">
			<?php
			$result = mysql_query("SELECT * FROM gvideos WHERE preview_hidden = '".$get_preview."' ORDER by time_hidden DESC;");

			$result2 = mysql_query("SELECT email from glogin_users WHERE '".$person->email."' LIKE '%@any.tv%'");
			$row2 = mysql_fetch_assoc($result2);			
			
			while($row = mysql_fetch_assoc($result))
			{
				if(!(empty($row["game_videos"])))
				{
				$json_output = file_get_contents("http://gdata.youtube.com/feeds/api/videos/".$row['game_videos']."?v=2&alt=json");
				$json = json_decode($json_output, true);
				$video_description = $json['entry']['media$group']['media$description']['$t'];
				$view_count = $json['entry']['yt$statistics']['viewCount'];
				$video_title = $json['entry']['title']['$t'];
				$video_author = $json['entry']['author']['0']['name']['$t'];
				$video_uploaded = $json['entry']['media$group']['yt$uploaded']['$t'];
				$view_likes = $json['entry']['yt$rating']['numLikes'];
				$video_thumbnail = $json['entry']['media$group']['media$thumbnail']['0']['url'];
				$json = json_decode($json_output, true);
				
				echo	'<table id="gameplayVideoPlaylist" class="table table-striped table-hover">
						<tr onclick="pop(\''.$row["game_videos"].'\')">
							<td>
								<img src="'.$video_thumbnail.'" style="width:120px; height:70px; border:1px solid #FDB900;" onclick="pop(\''.$row["game_videos"].'\')">
							</td>
							<td>
								<small style="color:#FDB900;">'.$video_title.'</small><br/>
								<small><small>Uploaded by: </small><i style="color:#FDB900;">'.$video_author.'</i></small><br/>
								<small><small>Views: </small><i style="color:#FDB900;">'.$view_count.'</i> <small>Likes: </small><i style="color:#FDB900;">'.$view_likes.'</i></small><br/>';
								
								
							/////////////Gawan ng paraan para mafilter ung guest sa admin/////////////
							
							
					
					
							if($row2['email'] == true)

							{
								echo '<a onclick="document.getElementById(\'idhidden\').value='.$row['id'].'; document.getElementById(\'previewhidden\').value=\''.$get_preview.'\'" href="#videoDelete" data-toggle="modal" style="color:white" class="pull-right"><i class="glyphicon glyphicon-trash"></i></a>';
							}
							
							elseif($row2['email'] == false)
							{
								echo '';
							}
							
							elseif(!(isset($person->email)))
							{
								echo '<a href="#videoDelete" data-toggle="modal" style="color:white" class="pull-right"><i class="glyphicon glyphicon-trash"></i></a>';
							}

								
							//////////////////////////////////////////////////////////////////////////
					
								
	
								

						echo	'<input type="text" name="previewhidden" value="'.$get_preview.'" style="display:none;">
						
								<input type="text" name="idhidden" value="'.$row['id'].'" style="display:none;">
								
								
								
								</form>
							</td>';
							
							
							
				?>
				
				<div class="modal fade" id="videoDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
					  <div class="modal-content">
						<div class="modal-header">
						  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
						  <h4 class="modal-title">Delete video</h4>
						</div>
						<div class="modal-body">
						  <p>Are you sure you want to delete this video?</p>
						</div>
						<div class="modal-footer">
						  
						  
						  
						  
						  
						  <form name="input" action ="deletevid.php" method="POST">
						  
						  
							  <input id="previewhidden" type="text" name="previewhidden" value="" style="display:none;"/>
							  
							  <input id="idhidden" type="text" name="idhidden" value="" style="display:none;"/>
							  <button type="button" class="btn btn-default" data-dismiss="modal">No, don't delete it!</button>
							  
							  
								
							 
							 
					
							   <button type="submit" href="deletevid.php" name="deletevidbtn" class="btn btn-primary">Delete This video!</button>
						   
						   
						   
						  </form> 
						</div>
					  </div>
					</div>
				  </div>
				  </div>
				  
				<?php
				 
				}
				
				else
				{
				
				}
			}
				if(mysql_num_rows($result) == false)
				{
					echo	'<br/><br/><h2 style="text-align:center; font-family:outagecut; color:#888;">No videos available at the moment.</h2>';
				}
			?>
				
				</tr>
			</table>		
		</div>
		<br/><br/><br/><br/>
		
		<div id="gi" class="panel panel-default">
			<div id="gi" class="panel-heading">
			  <h4 class="panel-title" style="font-family:outagecut;">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
				 <span class="glyphicon glyphicon-info-sign"></span> General Information
				</a>
			  </h4>
			</div>
			<div id="collapseOne" class="panel-collapse collapse in">
			  <div class="panel-body">
					<?php
					$result = mysql_query("SELECT game_name, game_alias, game_site, game_publisher, game_pvp, game_genre FROM gen_info WHERE game_alias = '".$get_preview."'");
					while ($row = mysql_fetch_assoc($result)){
						echo 'Name: ';
						echo '<strong style="color:white;">'.$row["game_name"].'</strong><br/>';
						echo 'Also Known As: ';
						echo '<strong style="color:white;">'.$row["game_alias"].'</strong><br/>';
						echo 'Official / Related Site: ';
						echo '<a href="'.$row["game_site"].'" target="_blank">';
						echo '<strong>'.$row["game_site"].'</strong></a><br/>';
						echo 'Publisher: ';
						echo '<strong style="color:white;">'.$row["game_publisher"].'</strong><br/>';
						echo 'Player vs. Player (PVP) option available? ';
						echo '<strong style="color:white;">'.$row["game_pvp"].'</strong><br/>';
						echo 'Genre: ';
						echo '<strong style="color:white;">'.$row["game_genre"].'</strong><br/>';
					}
					?>
			  </div>
			</div>
		  </div>
		  
		  <div id="ss" class="panel panel-default">
			<div id="ss" class="panel-heading">
			  <h4 class="panel-title" style="font-family:outagecut;">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
				  <span class="glyphicon glyphicon-wrench"></span> System Specifications
				</a>
			  </h4>
			</div>
			<div id="collapseTwo" class="panel-collapse collapse in">
			  <div class="panel-body">
				<?php
				$result = mysql_query("SELECT game_os, game_processor, game_videocard, game_harddrive, game_ram FROM gen_info WHERE game_alias = '".$get_preview."'");
				while ($row = mysql_fetch_assoc($result)){
					echo 'Operating System required to play game (At Least): ';
					echo '<strong style="color:white;">'.$row["game_os"].'</strong><br/>';
					echo 'Processor required to play game (At Least): ';
					echo '<strong style="color:white;">'.$row["game_processor"].'</strong><br/>';
					echo 'Videocard size required to play game (At Least): ';
					echo '<strong style="color:white;">'.$row["game_videocard"].'</strong><br/>';
					echo 'Hard Disk Drive (HDD) size required to store game (At Least): ';
					echo '<strong style="color:white;">'.$row["game_harddrive"].'</strong><br/>';
					echo 'Random Access Memory (RAM) size required to play game (At Least): ';
					echo '<strong style="color:white;">'.$row["game_ram"].'</strong><br/>';
				}
				?>
			  </div>
			</div>
		  </div>
		  
		  
		  
		<div id="od" class="panel panel-default">
			<div id="od" class="panel-heading">
			  <h4 class="panel-title" style="font-family:outagecut;">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
				  <span class="glyphicon glyphicon-warning-sign"></span> Other Details
				</a>
			  </h4>
			</div>
			<div id="collapseThree" class="panel-collapse collapse in">
			  <div class="panel-body">
			  
				<?php
				$result = mysql_query("SELECT * FROM additional_info WHERE hidden = '".$get_preview."'");
				$row = mysql_fetch_assoc($result);
				
				
				$result2 = mysql_query("SELECT email from glogin_users WHERE email LIKE '%@any.tv%' AND email = '".$person->email."'");
				$row2 = mysql_fetch_assoc($result2);	
					
				if($row2['email'] !== null)
				{	
					if($row['id'] == null)
					{
						echo	'<strong style="color:#FDB900;">Add other details for the game here:</strong><br/><br/>
						
							<form name="input" action="details.php" method="POST">
						
							Subtitles: <select name="subtitles" class="span2" required><option value="Yes">Yes</option><option value="Yes">No</option><br/></select><br/><br/>
							
							In-game help system: <select name="help" class="span2" required><option value="Yes">Yes</option><option value="No">No</option></select><br/><br/>
							
							
							Top-up feature: <select name="topup" class="span2" required><option value="Yes">Yes</option><option value="No">No</option></select><br/><br/>
							
							
							Link for latest news: <input type="text" name="latestnews" placeholder="Paste URL here" class="span5" required/><br/><br/>
							
							Link for latest guides: <input type="text" name="latestguides" placeholder="Paste URL here" class="span5" required/><br/><br/>
							
							Short description / overview for the game:<textarea placeholder="Post short description of game here" name="shortdescription" style="resize:none; width:425px;" rows="5" required></textarea><br/>
							
							<input type="text" name="detailshidden" value="'.$get_preview.'" style="display:none;"/><br/>
						
						<input type="submit" name="detailsbtn" class="btn btn-success pull-right"/>
						
						</form>';
			
					}
					elseif($row['id'] !== null)
					{
					echo
						'Subtitles: 
						<strong style="color:white;">'.$row["subtitles"].'</strong><br/>
						In-game Help System: 
						<strong style="color:white;">'.$row["help"].'</strong><br/>
						Top-up feature: 
						<strong style="color:white;">'.$row["topup"].'</strong><br/>
						Link for latest news: 
						<a href="'.$row["news"].'" target="_blank">
						<strong>'.$row["news"].'</strong></a>
						<br/>
						Link for latest guides: 
						<a href="'.$row["guides"].'" target="_blank">
						<strong>'.$row["guides"].'</strong></a>
						<br/>
						Short description / overview for the game: 
						<strong style="color:white;">'.$row["description"].'</strong>';
					}
				}
				elseif($row2['email'] == null)
				{
					if($row['id'] == null)
					{
						echo	'Additional information for this game is not available yet. we\'ll get this up soon!';
					}
					elseif($row['id'] !== null)
					{
						echo
						'Subtitles: 
						<strong style="color:white;">'.$row["subtitles"].'</strong><br/>
						In-game Help System: 
						<strong style="color:white;">'.$row["help"].'</strong><br/>
						Top-up feature: 
						<strong style="color:white;">'.$row["topup"].'</strong><br/>
						Link for latest news: 
						<a href="'.$row["news"].'" target="_blank">
						<strong>'.$row["news"].'</strong></a>
						<br/>
						Link for latest guides: 
						<a href="'.$row["guides"].'" target="_blank">
						<strong>'.$row["guides"].'</strong></a>
						<br/>
						Short description / overview for the game: 
						<strong style="color:white;">'.$row["description"].'</strong>';
					}
					
				}
				
				//var_dump($person->email);
				//var_dump($row2['email']);
				//var_dump("SELECT email FROM glogin_users WHERE email LIKE '".$person->email."'");
				
				?>
				</div>
			</div>
		</div>
		
		<div id="ur" class="panel panel-default">
			<div id="ur" class="panel-heading">
			  <h4 class="panel-title" style="font-family:outagecut;">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
				  <span class="glyphicon glyphicon-comment"></span> User Reviews
				</a>
			  </h4>
			</div>
			<div id="collapseFive" class="panel-collapse collapse in">
			  <div id="reviewBody" class="panel-body">

			  
			  <?php
			  if(isset($_SESSION['user_id'])){
			  echo'
				<form name="input" action ="review.php" method="POST">
					<textarea name="review" placeholder="Post your review here!" class="col-md-12" rows="5" style="resize:none; margin-bottom:5px;" required></textarea><br/>
					
					<input type="text" name="invisible" value="'.$get_preview.'" style="display:none;"></input>
					
					<input type="text" id="emailhidden" name="emailhidden" value="'.$person->email.'" style="display:none;"/>
					<input type="text" id="timehidden" name="timehidden" value="'.$date_time.'" style="display:none;"/>
					
					<input type="submit" name="reviewbtn"  data-loading-text="..." class="btn btn-success pull-right"/>
				</form><br/><br/><hr/>';
				}
				else
				{
					echo '<p style="text-align:center;">You must login first to be able to post a review.</p><hr/>';
				}?>
				
				
				<?php
				$result = mysql_query("SELECT game_reviews, preview_hidden, email_hidden, time_hidden FROM greview WHERE preview_hidden = '".$get_preview."'");
				
				echo	'<div style="overflow-y:scroll; height:350px; padding-right:10px;">';
					while ($row = mysql_fetch_assoc($result))
					{
					if(!($row['game_reviews'] == ""))
						{
							$result2 = mysql_query("SELECT photo FROM glogin_users WHERE email = '".$row['email_hidden']."'");
							$row2 = mysql_fetch_assoc($result2);
							
							echo	'<div class="news-item" id="news-1" style="font-family:Tahoma, Geneva, sans-serif">';
								echo	'<b style="color:#fdb900;"><img src="'.$row2['photo'].'" style="width:20px; margin-bottom:10px;"/> '.$row['email_hidden'].'</b> says:';
								echo	'<br/><p style="text-align:justify">"'.$row['game_reviews'].'"</p>';
								echo	'<p class="pull-right" style="font-size:10px;">Posted on: '.$row['time_hidden'].'</p>';
								echo	'</div><br/><hr/>';
								
						}
					}
				echo	'</div>';
				?>
				</div>	
			</div>		
		</div>
		
					
	
	</div>
	<br/><br/>
	
	
	
	<div id="disqus_thread"></div>
    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = 'gameplay-tm'; // required: replace example with your forum shortname

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
    
	
	

	<hr/>

	<a href="https://www.facebook.com/anyTVnetwork" target="_blank">Facebook</a> &nbsp <a href="https://twitter.com/anyTVnetwork" target="_blank">Twitter</a> &nbsp <a href="https://plus.google.com/109971475987405213729" target="_blank">Google+</a> &nbsp <a href="http://www.youtube.com/user/anyTVnetwork" target="_blank">YouTube</a><br/><br/>
	Check us out on our links above!<br/>
	Made by the awesome guys from <a href="http://www.any.tv" target="_blank">any.TV.</a><br/>
	Based on <a href="http://getbootstrap.com">Bootstrap</a>. Icons from <a href="http://glyphicons.com/" target="_blank">Glyphicons</a>.<br/>
	<a href="http://www.any.tv" target="_blank">any.TV Limited</a> &copy 2013 | Believe in You!<br/><br/>
	</div>
	
	
	<script type="text/javascript">
    /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
    var disqus_shortname = 'gameplay-tm'; // required: replace example with your forum shortname

    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function () {
        var s = document.createElement('script'); s.async = true;
        s.type = 'text/javascript';
        s.src = '//' + disqus_shortname + '.disqus.com/count.js';
        (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
    }());
    </script>
    

	
</body>


	
	
	
	<script src="js/bootstrap3js/bootstrap.min.js" type="text/javascript"></script>
	<script src="js/jquery.barrating.js" type="text/javascript"></script>
	<script type="text/javascript">
        $(function () {
            $('.rating-enable').click(function () {
                $('#example-a').barrating();
                $(this).addClass('deactivated');
                $('.rating-disable').removeClass('deactivated');
            });

            $('.rating-disable').click(function () {
                $('select').barrating('destroy');
                $(this).addClass('deactivated');
                $('.rating-enable').removeClass('deactivated');
            });

            $('.rating-enable').trigger('click');
        });
		
		function pop(id){
			  $('#playVideo').html('<iframe width="650" height="400" src="http://www.youtube.com/embed/'+id+'"/>'); 
		}
		
		function changeVideo()
		{
		var str=document.getElementById("demo").value; 
		var n=str.replace("http://www.youtube.com/watch?v=","");
		document.getElementById("demo").value=n;
		}
		
		function myFunction() {
			var fields = new Array("a", "b", "c", "d", "e");
			var average = new Array();
			var count = 0;
			for (a = 0; a < fields.length; a++) {
				if (document.getElementById(fields[a]).value != "") {
					average[count] = Number(document.getElementById(fields[a]).value);
					count++;
				}
			}
			var temp = 0;
			for (a = 0; a < average.length; a++) {
				temp+=average[a];
			}
			document.getElementById('elmID').value=(temp/average.length)+" out of 5";
		}
		
		$(function() {
			var limit = 35; // Change to what you want
			$('.news-item p').each(function(idx, el) {
			   var $p = $(el);
				if ($p.text().length > limit) {
					var whole_text = $p.text();
					$p.text(whole_text.substring(0, limit)+"...");
					$('<span class="read-more-content">').html(whole_text.substring(limit)).hide().appendTo($p);
					$('<button class="btn btn-link btn-sm pull-right" href="" style="color:#FDB900;">Read more</button>').click(function() {
						$(this).siblings('.read-more-content').fadeIn();
						$(this).remove();
					}).appendTo($p);
				}            
			});
			
		});
	
			
		</script>
		
		<script type="text/javascript">
				$("search").keypress(function(event) {
			if (event.which == 13) {
				event.preventDefault();
				$("form").submit();
			}
		});	
		</script>
		
		
	
</html>


