<?php
session_start();
if ($_SERVER["HTTPS"] != "on") {
	header("location: " . "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]); 
	exit(); 
}
if (!empty($_SESSION["SESS_ID"])) {
	header('location: index.php');
	exit();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>abizeitung Kommentare</title>
		<meta name="viewport" content="user-scalable = no, initial-scale = 1.0, maximum-scale = 1.0, width=device-width">
		<link rel="icon" type="image/png" href="images/favicon.png">
		<link rel="apple-touch-icon-precomposed" href="images/iosicon.png"/>
		<link rel="stylesheet" type="text/css" href="stylesheet/login.css">
		<link rel="stylesheet" type="text/css" href="stylesheet/global.css">
		<script type="text/javascript" src="js/WebKitDetect.js"></script>
		<script type="text/javascript" src="js/global.js"></script>
		<script type="text/javascript" src="js/json2.js"></script>
		<script type="text/javascript" src="js/login.js"></script>
		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-28790509-1']);
		  _gaq.push(['_setDomainName', '.jangxx.com']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>
	</head>
	<body onload="load()">
		<div id="login-box">
			<div id="login-box-header"></div>
			<div id="login-box-info"></div>
			<div id="login-fields-background">
				<div class="login-input" style="width: 200px;"><input id="input_username" type="text" class="login-input-field" placeholder="Benutzername"></div>
				<div class="login-input" style="width: 200px;"><input id="input_password" type="password" class="login-input-field" placeholder="Passwort"></div>
			</div>
		<div id="save-login-container" class="button-center" style="width: 200px;"></div>
		<div id="login-spinner-container"></div>
		<div class="button_1 button-center" onclick="login()">Login</div>
		</div>
	</body>
</html>