<?php

// Includes

header('Cache-Control: private, max-age=0');
header('Expires: -1');
header('Content-Type: text/html; charset=UTF-8');


require_once 'includes/google-api-php-client/apiClient.php';
require_once 'includes/google-api-php-client/contrib/apiOauth2Service.php';
require_once 'includes/idiorm.php';
require_once 'includes/relativeTime.php';

// Session. Pass your own name if you wish.

session_name('tzine_demo');
session_start();

// Database configuration with the IDIORM library

$host = 'localhost';
$user = 'anytv_ron';
$pass = '09213972063';
$database = 'anytv_gbase';

ORM::configure("mysql:host=$host;dbname=$database");
ORM::configure('username', $user);
ORM::configure('password', $pass);

// Changing the connection to unicode
ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

// Google API. Obtain these settings from https://code.google.com/apis/console/

$redirect_url = 'http://www.gameplay.tm/home.php'; // The url of your web site
$client_id = '654088323109.apps.googleusercontent.com';
$client_secret = '0PknYjZkFyMc6tim-lW7ipuJ';
$api_key = 'AIzaSyCcen55u9auR02OpKMVTjcQlnXjTG_Y76E';
