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

if(!isset($person->email))
{
	header('Location:home.php');
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
	
	<title>gameplay.tm | <?php echo $person->name; ?></title>
	<link rel="icon" type="image/png" href="gameplay-circ-black.ico" style="width:16px; height:16px;"/>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link href="bootstrap.min.css" rel="stylesheet" media="screen"/>
	
	<link href="externals.css?2212" rel="stylesheet" media="screen"/>
	
	
	<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />
	<link href="lightbox/css/lightbox.css" rel="stylesheet" type="text/css"/>
	

</head>

<body style="font-family:outagecut;">

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
			<li><a href="?logout" name="logout" class="logoutButton"><span class="glyphicon glyphicon-log-out"></span> &nbsp<b>Sign-out</b></a></li>
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








	<div id="profileBody" class="container">
	
		<div class="row">
		
		
			<div id="profileLeft" class="col-md-8">
				<br/><br/><br/>
				
			
				<p style="font-family:Tahoma, Geneva, sans-serif; font-size:12px;">This is your achievement map. Continue being active to unlock other achievements. More achievements will be added in the future.</p>
				
	
						
				<div id="trophyRow" class="row">
				
				<link href="profilestyle.css?241654351" rel="stylesheet" media="screen"/>
				<?php
			
					$result = mysql_query("SELECT COUNT(email_hidden) FROM guserrating WHERE email_hidden = '".$person->email."'");
					$row = mysql_fetch_assoc($result);
					
					$result2 = mysql_query("SELECT COUNT(email_hidden) FROM gvideos WHERE email_hidden = '".$person->email."'");
					
					$result3 = mysql_query("SELECT COUNT(email_hidden) FROM greview WHERE email_hidden = '".$person->email."'");
					$row3 = mysql_fetch_assoc($result3);
					
					while($row2 = mysql_fetch_assoc($result2))
					{
					
						if($row['COUNT(email_hidden)'] == 0)
						{
						
							echo	'<div id="trophy1" title="Congratulations! It\'s your first time to rate a game! You should treat everyone to pizza!"></div>';
							echo	'<div id="trophy1" style="position:relative; left:110px; bottom:55px;" title="Wow you\'re really getting the hang of this rating thing."></div>';
							echo	'<div id="trophy1" style="position:relative; left:220px; bottom:110px;" title="You\'re rating everything man! But do you even try the game before you rate it?"></div>';
							
						}
						
						elseif($row['COUNT(email_hidden)'] >= 1)
						{
						
							echo	'<div id="trophy1" title="Congratulations! It\'s your first time to rate a game! You should treat everyone to pizza!"><span class="glyphicon glyphicon-ok"></span></div>';
							echo	'<div id="trophy1" style="position:relative; left:110px; bottom:55px;" title="Wow you\'re really getting the hang of this rating thing."></div>';
							echo	'<div id="trophy1" style="position:relative; left:220px; bottom:110px;" title="You\'re rating everything man! But do you even try the game before you rate it?"></div>';
							
						}
						
						elseif($row['COUNT(email_hidden)'] >= 50)
						{
						
							echo	'<div id="trophy1" title="Congratulations! It\'s your first time to rate a game! You should treat everyone to pizza!"><span class="glyphicon glyphicon-ok"></span></div>';
							echo	'<div id="trophy1" style="position:relative; left:110px; bottom:55px;" title="Wow you\'re really getting the hang of this rating thing."><span class="glyphicon glyphicon-ok"></span></div>';
							echo	'<div id="trophy1" style="position:relative; left:220px; bottom:110px;" title="You\'re rating everything man! But do you even try the game before you rate it?"></div>';
							
						}
						
						elseif($row['COUNT(email_hidden)'] >= 100)
						{
						
							echo	'<div id="trophy1" title="Congratulations! It\'s your first time to rate a game! You should treat everyone to pizza!"><span class="glyphicon glyphicon-ok"></span></div>';
							echo	'<div id="trophy1" style="position:relative; left:110px; bottom:55px;" title="Wow you\'re really getting the hang of this rating thing."><span class="glyphicon glyphicon-ok"></span></div>';
							echo	'<div id="trophy1" style="position:relative; left:220px; bottom:110px;" title="You\'re rating everything man! But do you even try the game before you rate it?"><span class="glyphicon glyphicon-ok"></span></div>';
							
						}
						
						
						
						
						if($row2['COUNT(email_hidden)'] == 0)
						{
							
							echo
							'<div id="trophy1" style="position:relative; left:56px; bottom:77px;" title="No other site lets users do this. I think."></div>';
							
							echo '<div id="trophy1" style="position:relative; left:166px; bottom:132px;" title="Yeah, pretty sure we\'re the only ones who let their users do this."></div>';
							
							echo '<div id="trophy1" style="position:relative; left:110px; bottom:99px;" title="Okay not because we let you do this, doesn\'t mean you should spam it. Just kidding."></div>';
						}

						elseif($row2['COUNT(email_hidden)'] >= 1)
						{
							
							echo
							'<div id="trophy1" style="position:relative; left:56px; bottom:77px;" title="No other site lets users do this. I think."><span class="glyphicon glyphicon-ok"></span></div>';
							
							echo '<div id="trophy1" style="position:relative; left:166px; bottom:132px;" title="Yeah, pretty sure we\'re the only ones who let their users do this."></div>';
							
							echo '<div id="trophy1" style="position:relative; left:110px; bottom:99px;" title="Okay not because we let you do this, doesn\'t mean you should spam it. Just kidding."></div>';
						}
						
						elseif($row2['COUNT(email_hidden)'] >= 50)
						{
							
							echo
							'<div id="trophy1" style="position:relative; left:56px; bottom:77px;" title="No other site lets users do this. I think."><span class="glyphicon glyphicon-ok"></span></div>';
							
							echo '<div id="trophy1" style="position:relative; left:166px; bottom:132px;" title="Yeah, pretty sure we\'re the only ones who let their users do this."><span class="glyphicon glyphicon-ok"></span></div>';
							
							echo '<div id="trophy1" style="position:relative; left:110px; bottom:99px;" title="Okay not because we let you do this, doesn\'t mean you should spam it. Just kidding."></div>';
						}
						
						elseif($row2['COUNT(email_hidden)'] >= 100)
						{
							
							echo
							'<div id="trophy1" style="position:relative; left:56px; bottom:77px;" title="No other site lets users do this. I think."><span class="glyphicon glyphicon-ok"></span></div>';
							
							echo '<div id="trophy1" style="position:relative; left:166px; bottom:132px;" title="Yeah, pretty sure we\'re the only ones who let their users do this."><span class="glyphicon glyphicon-ok"></span></div>';
							
							echo '<div id="trophy1" style="position:relative; left:110px; bottom:99px;" title="Okay not because we let you do this, doesn\'t mean you should spam it. Just kidding."><span class="glyphicon glyphicon-ok"></span></div>';
						}
						
						
						
						
						
						if($row3['COUNT(email_hidden)'] == 0)
						{
							
							
							echo	'<div id="trophy1" style="position:relative; left:56px; bottom:65px;" title="You\'re actually commenting on something other than your hot neighbor\'s status update!"></div>';
							
							echo 	'<div id="trophy1" style="position:relative; left:166px; bottom:120px;" title="Commenting on your hot neightbor\'s status update did get stuck on you, didn\'t it?"></div>';
							
							echo 	'<div id="trophy1" style="position:relative; left:111px; bottom:87px;" title="Commenting on your hot neightbor\'s status update did get stuck on you, didn\'t it?"></div>';
							

						}
						
						if($row3['COUNT(email_hidden)'] >= 1)
						{
							
							
							echo	'<div id="trophy1" style="position:relative; left:56px; bottom:65px;" title="You\'re actually commenting on something other than your hot neighbor\'s status update!"><span class="glyphicon glyphicon-ok"></span></div>';
							
							echo 	'<div id="trophy1" style="position:relative; left:166px; bottom:120px;" title="Commenting on your hot neightbor\'s status update did get stuck on you, didn\'t it?"></div>';
							
							echo 	'<div id="trophy1" style="position:relative; left:111px; bottom:87px;" title="Commenting on your hot neightbor\'s status update did get stuck on you, didn\'t it?"></div>';
							

						}
						
						elseif($row3['COUNT(email_hidden)'] >= 50)
						{
							
							
							echo	'<div id="trophy1" style="position:relative; left:56px; bottom:65px;" title="You\'re actually commenting on something other than your hot neighbor\'s status update!"><span class="glyphicon glyphicon-ok"></span></div>';
							
							echo 	'<div id="trophy1" style="position:relative; left:166px; bottom:120px;" title="Commenting on your hot neightbor\'s status update did get stuck on you, didn\'t it?"><span class="glyphicon glyphicon-ok"></span></div>';
							
							echo 	'<div id="trophy1" style="position:relative; left:111px; bottom:87px;" title="Commenting on your hot neightbor\'s status update did get stuck on you, didn\'t it?"></div>';
							

						}
						
						elseif($row3['COUNT(email_hidden)'] >= 100)
						{
							
							
							echo	'<div id="trophy1" style="position:relative; left:56px; bottom:65px;" title="You\'re actually commenting on something other than your hot neighbor\'s status update!"><span class="glyphicon glyphicon-ok"></span></div>';
							
							echo 	'<div id="trophy1" style="position:relative; left:166px; bottom:120px;" title="Commenting on your hot neightbor\'s status update did get stuck on you, didn\'t it?"><span class="glyphicon glyphicon-ok"></span></div>';
							
							echo 	'<div id="trophy1" style="position:relative; left:111px; bottom:87px;" title="Commenting on your hot neightbor\'s status update did get stuck on you, didn\'t it?"><span class="glyphicon glyphicon-ok"></span></div>';
							

						}	
						
					}
				
				?>
			
				</div>
			</div>
			
			
			
			<div class="col-md-4" style="height:1000px; background-color:blue">
			<br/><br/><br/>
			<?php
			
			echo
				'<ul class="media-list">
				  <li class="media">
					<a class="pull-left" href="#">
					  <img class="media-object" src="'.$person->photo.'" alt="'.$person->name.'">
					</a>
					<div class="media-body">
					  <h4 class="media-heading">Media heading</h4>
					  ...
					</div>
				  </li>
				</ul>';
			
			?>
				
			</div>
			
			
			
			
		</div>

	</div>
			
			
			
	




	<div id="profileContainer" class="container" style="padding-left:36px;">
	
		
	
	
		<div class="container" id="profileInfoContainer">
			<div class="row">
				<div id="imageContainer" class="col-md-2">
					<img id="profileImage" src="assets/img/default_avatar.jpg"/>
				</div>
				
				<div class="col-md-8" id="profileNameContainer">
					<?php
						echo '<p style="font-size:40px;">'.htmlspecialchars($person->name).'</p>';
						echo '<p style="margin-top:-20px; font-size:20px; font-family:Georgia, serif">Email: <b>'.htmlspecialchars($person->email).'</b></p>';
						echo '<p style="margin-top:-15px; font-size:20px; font-family:Georgia, serif">Registered: <b>'.new RelativeTime($person->registered).'</b></p>';
						echo '<p style="margin-top:-15px; font-size:20px; font-family:Georgia, serif">Games I love to play:</p>';
						
						echo '<div id="navcontainer">
							<ul>
								<a href="img/fpsbadge.gif" data-lightbox="Badges" title="First-Person Shooter Badge *Pew! Pew!*">
									<li>
										<img id="badgeImage" src="img/fpsbadge.gif"/>
									</li>
								</a>								
								<a href="img/mobabadge.gif" data-lightbox="Badges" title="Battle Arena Badge *Haha noob!*">
									<li>									
										<img id="badgeImage" src="img/mobabadge.gif"/>									
									</li>
								</a>
								<a href="img/mmorpgbadge.gif" data-lightbox="Badges" title="Role-Playing Badge *Heal me!*">
									<li>
										<img id="badgeImage" src="img/mmorpgbadge.gif"/>									
									</li>
								</a>
							</ul>
							</div>';
							
					?>
				</div>	
			</div>
		</div>
		<hr/>
		
		
		
			<div class="row">
			
				<div class="col-md-4">
					<div id="ss" class="panel panel-default">
						<div id="ss" class="panel-heading">
						  <h4 class="panel-title" style="font-family:outagecut;">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#recentVideos">
							  <span class="glyphicon glyphicon-facetime-video"></span> My Recent Videos
							</a>
						  </h4>
						</div>
						<div id="recentVideos" class="panel-collapse collapse in">
						  <div class="panel-body">
							<div style="height:300px; overflow-y:scroll;">
							
							
							<?php
							
							$result = mysql_query("SELECT * FROM gvideos WHERE email_hidden = '".$person->email."' ORDER by time_hidden DESC LIMIT 3");
							
								echo	'<ul class="list-group">';
								
									while ($row = mysql_fetch_assoc($result))
										{
											$json_output = file_get_contents("http://gdata.youtube.com/feeds/api/videos/".$row['game_videos']."?v=2&alt=json");
											$json = json_decode($json_output, true);
											$video_title = $json['entry']['title']['$t'];
											$video_thumbnail = $json['entry']['media$group']['media$thumbnail']['0']['url'];
											$video_author = $json['entry']['author']['0']['name']['$t'];
											
												
												echo	'<li class="list-group-item">
												
															<div class="media">
															  <a class="pull-left" href="http://www.youtube.com/watch?v='.$row['game_videos'].'" target="_blank">
																<img class="media-object" src="'.$video_thumbnail.'"/>
															  </a>
															  <div class="media-body">
																<h4 class="media-heading">'.$video_title.'</h4>
																<p style="font-size:12px; font-family:Trebuchet MS, Helvetica, sans-serif;">Posted on: '.$row['time_hidden'].'</p>
																<p style="font-size:12px; font-family:Trebuchet MS, Helvetica, sans-serif;">Original Uploader: <i>'.$video_author.'</i></p>
															  </div>
															</div>
															
														</li>';

												
												
										}
								
								echo	'</ul>';
							?>
								
								
							</div>
						  </div>
						</div>
					  </div>
					  
				</div>
				
				<div class="col-md-4">
				
					<div id="ss" class="panel panel-default">
						<div id="ss" class="panel-heading">
						  <h4 class="panel-title" style="font-family:outagecut;">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#recentRating">
							  <span class="glyphicon glyphicon-thumbs-up"></span> My Recent Ratings
							</a>
						  </h4>
						</div>
						<div id="recentRating" class="panel-collapse collapse in">
						  <div class="panel-body">
						  
							<div style="height:300px; overflow-y:scroll;">
						
						
						<?php
							
							$result = mysql_query("SELECT * FROM guserrating WHERE email_hidden = '".$person->email."' ORDER by time_hidden DESC LIMIT 3");
							
							
								echo	'<ul class="list-group">';
								
									while ($row = mysql_fetch_assoc($result))
										{
												
												echo	'<li class="list-group-item" style="font-family:Trebuchet MS, Helvetica, sans-serif;">
												
															<p style="color:white; font-family:Trebuchet MS, Helvetica, sans-serif;"><b style="font-size:1em;"><b style="font-size:3em;">'.$row['rating'].'</b> out of 10</p>
															
															<p style="font-size:12px; font-family:Trebuchet MS, Helvetica, sans-serif;">"'.$row['rating_description'].'"</p>
															
															<p style="font-size:12px; font-family:Trebuchet MS, Helvetica, sans-serif;">- on '.$row['userrating_hidden'].'</p>
															
															
															
															<p style="font-size:12px; font-family:Trebuchet MS, Helvetica, sans-serif;">Rated on: '.$row['time_hidden'].'</p>
															
														</li>';

												
												
										}
								
								echo	'</ul>';
							?>
							
							
							</div>
								
						  </div>
						</div>
					  </div>
				
				</div>
				
				<div class="col-md-4">
				
					<div id="ss" class="panel panel-default">
						<div id="ss" class="panel-heading">
						  <h4 class="panel-title" style="font-family:outagecut;">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#recentComments">
							  <span class="glyphicon glyphicon-comment"></span> My Recent Comments
							</a>
						  </h4>
						</div>
						<div id="recentComments" class="panel-collapse collapse in">
						  <div class="panel-body">
							<div style="height:300px; overflow-y:scroll;">
							
							
						<?php
							
							$result = mysql_query("SELECT * FROM greview WHERE email_hidden = '".$person->email."' ORDER by time_hidden DESC LIMIT 3");
							
							
								echo	'<ul class="list-group">';
								
									while ($row = mysql_fetch_assoc($result))
										{
										echo	'<li class="list-group-item">';
											
											echo	'<p style="font-size:15px; color:white; font-family:Trebuchet MS, Helvetica, sans-serif;">"'.$row['game_reviews'].'"</p>';
											echo	'<p style="font-family:Trebuchet MS, Helvetica, sans-serif;">- on '.$row['preview_hidden'].'</p>';
											echo	'<p style="font-size:12px; font-family:Trebuchet MS, Helvetica, sans-serif;">Commented on: '.$row['time_hidden'].'</p>';
										echo	'</li>';		
												
										}
								
								echo	'</ul>';
							?>
								
								
							</div>
						  </div>
						</div>
					  </div>
				
				</div>
				
			</div>
		
		<hr/>
		
		
		
		<div style="font-family:outagecut; padding-bottom:40px;">			
		<a href="https://www.facebook.com/anyTVnetwork" target="_blank">Facebook</a> &nbsp <a href="https://twitter.com/anyTVnetwork" target="_blank">Twitter</a> &nbsp <a href="https://plus.google.com/109971475987405213729" target="_blank">Google+</a> &nbsp <a href="http://www.youtube.com/user/anyTVnetwork" target="_blank">YouTube</a><br/><br/>
		Check us out on our links above!<br/>
		Made by the awesome guys from <a href="http://www.any.tv" target="_blank">any.TV.</a><br/>
		Based on <a href="http://getbootstrap.com">Bootstrap</a>. Icons from <a href="http://glyphicons.com/" target="_blank">Glyphicons</a>.<br/>
		<a href="http://www.any.tv" target="_blank">any.TV Limited</a> &copy 2013 | Believe in You!		
				
	</div>

	</div>	
	
	
		</body>
		
		<script src="http://code.jquery.com/jquery.js" type="text/javascript"></script>
		<script src="asd/js/jquery.min.js" type="text/javascript"></script>
		<script type="text/javascript" language="javascript" src="jquery-ui-1.10.3/ui/jquery-ui.js"></script>
		<script type="text/javascript" src="js/jquery.autocomplete.js"></script>
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
		
		<script type="text/javascript">
				$("search").keypress(function(event) {
			if (event.which == 13) {
				event.preventDefault();
				$("form").submit();
			}
		});	
		</script>
		
		<script src="lightbox/js/lightbox-2.6.min.js"></script>
		<script src="js/bootstrap3js/bootstrap.min.js" type="text/javascript"></script>

</html>


