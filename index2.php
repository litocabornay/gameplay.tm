<?php
header('Cache-Control: private, max-age=0');
header('Expires: -1');
header('Content-Type: text/html; charset=UTF-8');



function getif($key, $default = '') { return array_key_exists($key, $_GET) ? $_GET[$key] : $default; }

if (!isset($get_preview)) $get_preview = getif('preview');

// Change this to the current web root
$myroot = '/';
$url = $_SERVER['REQUEST_URI'];


//FOR games
if (contains($url, '/dota2') || contains($url, '/dota-2') || contains($url, '/dota_2'))
{
	$get_preview = 'dota_2';
	require 'game.php';	
}
elseif (contains($url, '/allodsonline') || contains($url, '/allods-online') || contains($url, '/allods_online'))
{
	$get_preview = 'allods_online';
	require 'game.php';	
}
elseif (contains($url, '/warframe'))
{
	$get_preview = 'warframe';
	require 'game.php';	
}
elseif (contains($url, '/leagueoflegends') || contains($url, '/league-of-legends') || contains($url, '/league_of_legends'))
{
	$get_preview = 'league_of_legends';
	require 'game.php';	
}
elseif (contains($url, '/worldoftanks') || contains($url, '/world-of-tanks') || contains($url, '/world_of_tanks'))
{
	$get_preview = 'world_of_tanks';
	require 'game.php';	
}
elseif (contains($url, '/profile'))
{
	require 'profile.php';	
}

elseif (contains($url, '/forums'))
{
	require 'forums/index.php';	
}



else
{
	exit('Queried <b>'. $url . '</b>. Game not found in database.');
}

function contains($s, $find) { return false !== strpos($s, $find); }

exit;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html><head>

	<title>$_SERVER</title>

	<style type="text/css">/*<![CDATA[*/

html { border:none }
body { font-size:75%; font-family:verdana; margin:0px; padding:32px; background-color:#C0C0C0 }
	/*]]>*/</style>

</head><body>

<!-- ======================================================================= -->


<h1>$_SERVER</h1>

	<table cellspacing="8">
<?php

foreach ($_SERVER as $sName => $sValue)
	echo "<tr><td>$sName</td><td>$sValue</td></tr>";

?>
	</table>

<h1>Globals</h1>

	<table cellspacing="8">
<?php

$a = array('__FILE__' => __FILE__);

foreach ($a as $sName => $sValue)
	echo "<tr><td>$sName</td><td>$sValue</td></tr>";

?>
	</table>


<?php

/* does not work

showHash('$_GET');
showHash('$_SERVER');

function showHash($s)
{
	global $$s;

	$sHtml = '';

	$h = $$s;
	foreach ($h as $sName => $sValue)
	{
		$sHtml .= "<tr><td>$sName</td><td>$sValue</td></tr>";
	}

	echo <<<EOS

<h1>$s</h1>

	<table cellspacing="8">$sHtml</table>
EOS;
}

*/

?>


<!-- ======================================================================= -->

</body></html>