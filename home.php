<?php
function getif($key, $default = '') { return array_key_exists($key, $_GET) ? $_GET[$key] : $default; }

if (!isset($get_preview)) $get_preview = getif('preview');

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
	
	// This method will obtain the actuall access token from Google,
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
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">	
<head>
	<base href="<?php echo $myroot; ?>">
	<link rel="icon" type="image/png" href="gameplay-circ-black.ico" style="width:16px; height:16px;"/>
	
	<meta content="http://www.gameplay.tm" property="og:url">
	<meta content="g/gameplay-circ-black.png" property="og:image">
	<meta content="Gameplay.tm" property="og:title">
	<meta content="Welcome to any.TV Gameplay, where you can watch the gameplay of your favorite games, add videos, comment and gain achievements as well! What are you waiting for? Watch. Play. Conquer." property="og:description">
	
	<title>gameplay.tm</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="bootstrap.min.css?20130929" rel="stylesheet" media="screen"/>
	<link href="externals.css?20130929" rel="stylesheet" media="screen"/>
	<link href="inlinegallery.css?20130929" rel="stylesheet" media="screen"/>
	<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />

	<link rel="stylesheet" href="asd/css/jquery.fileupload-ui.css?20130929">
	<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css?20130929" />
	<noscript><link rel="stylesheet" href="asd/css/jquery.fileupload-ui-noscript.css?20130929"></noscript>
	
	
	<link rel="stylesheet" href="css/blueimp-gallery.min.css?20130929">
	<link rel="stylesheet" href="css/jquery.fileupload-ui.css?20130929">
	<noscript><link rel="stylesheet" href="css/jquery.fileupload-ui-noscript.css?20130929"></noscript>

	<style type="text/css">
			@import "css/jquery.dataTables.css";
			@import "css/dataTables.tabletools.css";
			@import "css/dataTables.editor.css";
	</style>
	
	<style type="text/css">
		.ac_results
		{
			border:1px solid #888;	
		}
	</style>
	

		
		<script src="asd/js/jquery.min.js?20130929"></script>
		<script type="text/javascript" language="javascript" src="jquery-ui-1.10.3/ui/jquery-ui.js?20130929"></script>
		
		<script type="text/javascript" language="javascript" charset="utf-8" src="js/jquery.dataTables.min.js?20130929"></script>
		<script type="text/javascript" language="javascript" charset="utf-8" src="js/dataTables.tabletools.min.js?20130929"></script>
		<script type="text/javascript" language="javascript" charset="utf-8" src="js/dataTables.editor.min.js?20130929"></script>
		<script type="text/javascript" language="javascript" charset="utf-8" src="js/table.gen_info.js?20130929"></script>
		
		

		
		<script language="javascript"> 
		
		function toggle() {
			var ele = document.getElementById("toggleText");
			var text = document.getElementById("displayText");
			var list = document.getElementById("listView");
			
			
			if(ele.style.display == "block")
				{
					ele.style.display = "none";
					list.style.display = "block";
					text.innerHTML = "Toggle Tile View";
				}
			else if(list.style.display == "block")
				{
					list.style.display = "none";
					ele.style.display = "block";
					text.innerHTML = "Toggle Table View";
				}
		} 
		</script>
		
		<script src="http://code.jquery.com/jquery.js?09302013" type="text/javascript"></script>
		<script src="asd/js/jquery.min.js?09302013" type="text/javascript"></script>
		<script type="text/javascript" language="javascript" src="jquery-ui-1.10.3/ui/jquery-ui.js?09302013"></script>
		

			
</head>




<div id="homeContainer" class="container">
		
		
		
		<div id="main" class="pull-right" style="margin-top:-70px;">
			
			<?php if($person):?>			
				
				Welcome, <a href="profile"><b><?php echo htmlspecialchars($person->email)?></b></a>
					
					<?php 
					$result = mysql_query("SELECT email FROM glogin_users WHERE email LIKE '%@any.tv%'");
					while ($row = mysql_fetch_assoc($result))
					{
					if($person->email == $row['email'])
					echo '[Admin]';
					}?>
					
					<?php 
					$result = mysql_query("SELECT email FROM glogin_users WHERE email LIKE '%@gmail.com%'");
					while ($row = mysql_fetch_assoc($result))
					{
					if($person->email == $row['email'])
					echo '[Guest]';
					}?>
					

				&nbsp|&nbsp <a href="?logout" name="logout" class="logoutButton">Logout</a>
			
			
			
            	<p class="register_info" style="text-align:center; text-align:right;">You registered <b><?php echo new RelativeTime($person->registered)?></b></p>
            	
			<?php else:?>
            	<a href="<?php echo $client->createAuthUrl()?>" class="googleLoginButton"><img src="siwg2.png" style="width:200px;  border-radius:10px 0 100px 0"/></a>
            <?php endif;?>

		</div>
		
		&nbsp <p style="margin-top:-90px;"><a href="/forums">Forums</a> | <a href="http://www.any.tv/staff/">Contribute</a> | <a href="http://www.any.tv/blog/what-is-any-tv/">About us</a></p>
		
	<br/>
	<div id="splashLogo" style="margin-left:285px; margin-bottom:-30px; margin-top:20px;">
			
		<img src="images/gameplay-tm-logo.png" style="margin-left:100px;"></img>
	
		<br/><br/><br/>
			
			<div class="input-group">
			
			 <span id="form" class="input-group-btn form-inline">
			  <input type="text" id="search" style="margin-left:-70px; border:1px solid #888; font-family:outagecut; font-size:15px; background-color:#060606;" onkeypress="searchClick()" class="form-control" placeholder="Find your game here!"></input>
				<button class="btn btn-default" type="button" style="border:1px solid #888; border-radius:0 4px 4px 0;"><span class="glyphicon glyphicon-search" style="padding-bottom:6px" onclick="searchClick()"/></button>
			 </span>
				
			  
			</div>
			
	</div>
	
	
	<br/><br/><br/>
	

		<div>
			<?php
	
				$result = mysql_query("SELECT email FROM glogin_users WHERE email LIKE '%@any.tv%'");
				while($row = mysql_fetch_assoc($result))
				{
					error_reporting(0);
					if($person->email == $row['email'])
					{
							  
						echo	
						'<table cellpadding="0" cellspacing="0" border="0" class="display" id="gen_info" width="100%" >
							<thead>
								<tr>
									<th>Name</th>
									<th>AKA</th>
									<th>Site</th>
									<th>Publisher</th>
									<th>PVP</th>
									<th>Genre</th>
									<th>OS</th>
									<th>Processor</th>
									<th>Videocard</th>
									<th>HDD</th>
									<th>RAM</th>
									<th>Added by:</th>
									<th>Added on:</th>
								</tr>
							</thead>
						</table><br/><hr/>';
					}
				}
			?>
			
			
	</div>
	
	
		<?php
			$result4 = mysql_query("SELECT email from glogin_users WHERE email LIKE '%@gmail.com%' AND email = '".$person->email."'");
			$row4 = mysql_fetch_assoc($result4);
			if($row4['email'] !== null)
			{
				//TILE VIEW
				$result3 = mysql_query("SELECT * FROM gen_info ORDER by game_name");
				echo '<br/><a id="displayText" class="pull-left btn btn-inverse" href="javascript:toggle();">Toggle Table View</a><br/><br/>';
				echo	'<div id="toggleText" style="display:block; height:370px;">';
				echo	'<ul>';
				
				while ($row3 = mysql_fetch_assoc($result3))
					{
						
					echo '<li id="imageHolder">
							<a href="'.$row3['game_alias'].'">';
							
					echo	'<img id="gameImage" src="server/php/files/'.$row3['game_alias'].'-cover.jpg"/>
								<span class="gameNameFront">';
								
					$result4 = mysql_query("SELECT rating, userrating_hidden FROM guserrating WHERE userrating_hidden = '".$row3['game_alias']."'");
					$row4 = mysql_fetch_assoc($result4);
	
						if($row4['rating'] !== null)
							{echo	'('.$row4['rating'].') ';}
						else
							{echo	'(NRY) ';}		
		
					echo		$row3['game_name'].'</span>
								<span class="gamePublisherFront">'.$row3['game_publisher'].'</span>
								<span class="gameGenreFront">'.$row3['game_genre'].'</span>
							
							</a>
						</li>';		
					}
				echo	'</ul>';
				echo	'</div>';
			}
			elseif(!isset($person->email))
			{
				//TILE VIEW
				$result3 = mysql_query("SELECT * FROM gen_info ORDER by game_name");
				echo '<br/><a id="displayText" class="pull-left btn btn-inverse" href="javascript:toggle();">Toggle Table View</a><br/><br/>';
				echo	'<div id="toggleText" style="display:block; height:370px;">';
				echo	'<ul>';
				
				while ($row3 = mysql_fetch_assoc($result3))
					{
						
					echo '<li id="imageHolder">
							<a href="'.$row3['game_alias'].'">';
							
					echo	'<img id="gameImage" src="server/php/files/'.$row3['game_alias'].'-cover.jpg"/>
								<span class="gameNameFront">';
								
					$result4 = mysql_query("SELECT rating, userrating_hidden FROM guserrating WHERE userrating_hidden = '".$row3['game_alias']."'");
					$row4 = mysql_fetch_assoc($result4);
	
						if($row4['rating'] !== null)
							{echo	'('.$row4['rating'].') ';}
						else
							{echo	'(NRY) ';}		
		
					echo		$row3['game_name'].'</span>
								<span class="gamePublisherFront">'.$row3['game_publisher'].'</span>
								<span class="gameGenreFront">'.$row3['game_genre'].'</span>
							
							</a>
						</li>';		
					}
				echo	'</ul>';
				echo	'</div>';
			}
		?>

			<!-- LIST VIEW -->
			<?php
			$result4 = mysql_query("SELECT email FROM glogin_users WHERE email LIKE '%@gmail.com%'");
			while($row4 = mysql_fetch_assoc($result4))
			{
			error_reporting(0);
			if(($person->email == null) || $person->email == $row4['email'])
					{
						
						$result3 = mysql_query("SELECT * FROM gen_info ORDER by game_name");
						
							
							echo	'<div id="listView" style="display:none;">';
								echo	'<table class="table table-hover table-striped" align="center">';
								
										echo	'<tr>';
											echo	'<th>';
											echo	'Rating';
											echo	'</th>';
											echo	'<th>';
											echo	'Name';
											echo	'</th>';
											echo	'<th>';
											echo	'Site';
											echo	'</th>';
											echo	'<th>';
											echo	'Publisher';
											echo	'</th>';
											echo	'<th>';
											echo	'Genre';
											echo	'</th>';
										echo	'</tr>';
										
									while ($row3 = mysql_fetch_assoc($result3))
									{	
										$result4 = mysql_query("SELECT rating, userrating_hidden FROM guserrating WHERE userrating_hidden = '".$row3['game_alias']."'");
										$row4 = mysql_fetch_assoc($result4);
									
										echo	'<tr>';
											echo	'<td>';
											
											if($row4['rating'] !== null)
												{echo	$row4['rating'].' out of 10';}
											else
												{echo	'Game not rated yet';}
											
											echo	'</td>';
											echo	'<td>';
											echo	'<a href="game.php?preview='.$row3['game_alias'].'"><b>'.$row3['game_name'].'</b></a>';
											echo	'</td>';
											echo	'<td>';
											echo	'<a href="'.$row3['game_site'].'" target="_blank">'.$row3['game_site'].'</a>';
											echo	'</td>';
											echo	'<td>';
											echo	$row3['game_publisher'];
											echo	'</td>';	
											echo	'<td>';
											echo	$row3['game_genre'];
											echo	'</td>';												
										echo	'<tr>';			
									}	
								echo	'</table>';
							echo	'</div>';
					}	
				}
			?>
		
		
		

			<div>
				<!-- The file upload form used as target for the file upload widget -->
				<form id="fileupload" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
					<!-- Redirect browsers with JavaScript disabled to the origin page -->
					<noscript><input type="hidden" name="redirect" value="http://blueimp.github.io/jQuery-File-Upload/"></noscript>
					<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
					
					
					
					
					<?php 
					
					
					$result2 = mysql_query("SELECT email from glogin_users WHERE email LIKE '%@any.tv%' AND email = '".$person->email."'");
					$row2 = mysql_fetch_assoc($result2);	
					
					if($row2['email'] !== null)
					{
					
							echo'<div class="row fileupload-buttonbar">
								
									<!-- The fileinput-button span is used to style the file input field as button -->
									
								<div class="pull-left" style="margin-left:20px;">
									
									
									<span class="btn btn-success btn-sm fileinput-button">
										<i class="glyphicon glyphicon-plus"></i>
										<span>Add Images</span>
										<input type="file" name="files[]" multiple>
									</span>
									<button type="submit" class="btn btn-primary start btn-sm">
										<i class="glyphicon glyphicon-upload"></i>
										<span>Upload all</span>
									</button>
									<button type="reset" class="btn btn-warning cancel btn-sm">
										<i class="glyphicon glyphicon-ban-circle"></i>
										<span>Cancel</span>
									</button>
									<button type="button" class="btn btn-danger delete btn-sm">
										<i class="glyphicon glyphicon-trash"></i>
										<span>Delete</span>
									</button>
									<input type="checkbox" class="toggle">
									<!-- The loading indicator is shown during file processing -->
									<span class="fileupload-loading"></span>
								</div>
							
								<!-- The global progress information -->
								<div class="col-lg-5 fileupload-progress fade">
									<!-- The global progress bar -->
									<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
										<div class="progress-bar progress-bar-success" style="width:0%;"></div>
									</div>
									<!-- The extended global progress information -->
									<div class="progress-extended">&nbsp;</div>
								</div>
							</div>
									
									
								<div class="well">For image uploading, please follow these steps:<br/>1. Rename the images to be uploaded with their name per word underscored. <b>(e.g., league_of_legends-ss.jpg, dota_2-cover.jpg, world_of_tanks-ss2.jpg)</b><br/>2. For cover photos, preferrably large resolution images, use the suffix \'-cover\' for the image to be identified as is. <b>(e.g. league_of_legends-cover.jpg, world_of_tanks-cover.jpg)</b><br/>3. For screenshots, preferrably mid resolution images, use the suffixes \'-ss\', \'-ss1\', \'-ss2\', \'-ss3\' and so on and so forth, for the image to be identified as is.<b>(e.g., league_of_legends-ss1, league_of_legends-ss2.jpg, league_of_legends.ss3.jpg)</b><br/><br/><b>Please do follow this convention or else the images will not appear on their respective games.</b></div>
								
								
							
								<div id="imageTable" style="height:477px; overflow-y:scroll">
									<table role="presentation" class="table table-striped">
										<tbody class="files">
										</tbody>
									</table>
								</div>	

							</form>
						  
						  
							<!-- The blueimp Gallery widget -->
							<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
								<div class="slides"></div>
								<h3 class="title"></h3>
								<a class="prev"></a>
								<a class="next"></a>
								<a class="close">X</a>
								<a class="play-pause"></a>
								<ol class="indicator"></ol>
							</div>
						</div>';
				
					}
					elseif($row2['email'] == null)
					{
						echo	'';
					}
			?>
			

			
		</div>
	</div>
</div>
	
	<div class="container" style="text-align:center; padding-bottom:20px;">
		Gameplay.tm &copy 2013 | Watch. Play. Conquer.<br/>
		any.TV Limited &copy 2013 | Believe in you!<br/>
	</div>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js" type="text/javascript"></script>
	<script src="http://code.jquery.com/jquery.js?20130929" type="text/javascript"></script>
	<script src="asd/js/jquery.min.js?20130929" type="text/javascript"></script>
	<script type="text/javascript" language="javascript" src="jquery-ui-1.10.3/ui/jquery-ui.js?20130929"></script>
	
	
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
			<script type="text/javascript">
					$("search").keypress(function(event) {
				if (event.which == 13) {
					event.preventDefault();
					$("form").submit();
				}
			});	
			</script>
	
	
			<div class="container">
				
			</div>
	
		
</body>
	

	<script src="js/vendor/jquery.ui.widget.js?20130929"></script>
	<script src="http://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js?20130929"></script>
	<script src="http://blueimp.github.io/JavaScript-Load-Image/js/load-image.min.js?20130929"></script>
	<script src="http://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js?20130929"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	<script src="http://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js?20130929"></script>
	<script src="js/jquery.iframe-transport.js?20130929"></script>
	<script src="js/jquery.fileupload.js?20130929"></script>
	<script src="js/jquery.fileupload-process.js?20130929"></script>
	<script src="js/jquery.fileupload-image.js?20130929"></script>
	<script src="js/jquery.fileupload-audio.js?20130929"></script>
	<script src="js/jquery.fileupload-video.js?20130929"></script>
	<script src="js/jquery.fileupload-validate.js?20130929"></script>
	<script src="js/jquery.fileupload-ui.js?20130929"></script>
	<script src="js/main.js?20130929"></script>
	<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
	<!--[if (gte IE 8)&(lt IE 10)]>
	<script src="js/cors/jquery.xdr-transport.js"></script>
	<![endif]-->
	
	
	<!-- The template to display files available for upload -->
	
	

		<script id="template-upload" type="text/x-tmpl">
		{% for (var i=0, file; file=o.files[i]; i++) { %}
			<tr class="template-upload fade">
				<td>
					<span class="preview"></span>
				</td>
				<td>
					<p class="name">{%=file.name%}</p>
					{% if (file.error) { %}
						<div><span class="label label-danger">Error</span> {%=file.error%}</div>
					{% } %}
				</td>
				<td>
					<p class="size">{%=o.formatFileSize(file.size)%}</p>
					{% if (!o.files.error) { %}
						<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
					{% } %}
				</td>
				<td>
					{% if (!o.files.error && !i && !o.options.autoUpload) { %}
						<button class="btn btn-primary start">
							<i class="glyphicon glyphicon-upload"></i>
							<span>Start</span>
						</button>
					{% } %}
					{% if (!i) { %}
						<button class="btn btn-warning cancel">
							<i class="glyphicon glyphicon-ban-circle"></i>
							<span>Cancel</span>
						</button>
					{% } %}
				</td>
			</tr>
		{% } %}
		</script>
	

	
	
	
	
		<!-- The template to display files available for download -->
		<script id="template-download" type="text/x-tmpl">
		{% for (var i=0, file; file=o.files[i]; i++) { %}
			<tr class="template-download fade">
				<td>
					<span class="preview">
						{% if (file.thumbnailUrl) { %}
							<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
						{% } %}
					</span>
				</td>
				<td>
					<p class="name">
						<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
					</p>
					{% if (file.error) { %}
						<div><span class="label label-important">Error</span> {%=file.error%}</div>
					{% } %}
				</td>
				
				<td>
					<span class="size">{%=o.formatFileSize(file.size)%}</span>
				</td>
					
				<?php
				if(isset($_SESSION['user_id']))
				{
				echo
				'<td id="deleteImages">
					<input type="checkbox" name="delete" value="1" class="toggle">
					<button style="color:white; margin-bottom:8px;" class="btn btn-link delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields=\'{"withCredentials":true}\'{% } %}>
						<span class="glyphicon glyphicon-trash"></span>		
					</button>
				</td>';
				}
				else
				{
					echo	'';
				}	
				?>	
			</tr>
		{% } %}

		</script>
		
	
		

			

</html>


