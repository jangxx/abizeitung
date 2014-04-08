<?php
require "api/constants.php";
session_start();
if ($_SERVER["HTTPS"] != "on") {
header("location: " . "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]); exit(); }
if ($_SESSION["SESS_ID"] == "") {
	header('location: login.php');
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="http-equiv" content="Content-type: text/html; charset=ISO-8859-1"/>
		<meta name="viewport" content="user-scalable = no, initial-scale = 1.0, maximum-scale = 1.0, width=device-width">
		<title>abizeitung Kommentare</title>
		<link rel="icon" type="image/png" href="images/favicon.png">
		<link rel="apple-touch-icon-precomposed" href="images/iosicon.png"/>
		<link rel="stylesheet" type="text/css" href="stylesheet/global.css">
		<link rel="stylesheet" type="text/css" href="stylesheet/main.css">
		<link rel="stylesheet" type="text/css" href="stylesheet/elections.css">
		<link rel="stylesheet" type="text/css" href="stylesheet/popup.css">
		<link rel="stylesheet" type="text/css" href="stylesheet/settings.css">
		<link rel="stylesheet" type="text/css" href="stylesheet/description.css">
		<link rel="stylesheet" type="text/css" href="stylesheet/quotes.css">
		<?php if ($_SESSION["SESS_ADMIN"] == 1) echo '<link rel="stylesheet" type="text/css" href="stylesheet/admin.css">' . "\n"; ?>
		<script type="text/javascript">
			var USER = <?php echo $_SESSION["SESS_ID"]; ?>;
		</script>
		<script type="text/javascript" src="js/global.js"></script>
		<script type="text/javascript" src="js/popup.js"></script>
		<script type="text/javascript" src="js/WebKitDetect.js"></script>
		<script type="text/javascript" src="js/json2.js"></script>		
		<script type="text/javascript" src="js/main.js" charset="ISO-8859-1"></script>
		<script type="text/javascript" src="js/settings.js" charset="ISO-8859-1"></script>
		<script type="text/javascript" src="js/elections.js" charset="ISO-8859-1"></script>
		<script type="text/javascript" src="js/description.js" charset="ISO-8859-1"></script>
		<script type="text/javascript" src="js/quotes.js" charset="ISO-8859-1"></script>
		<?php if ($_SESSION["SESS_ADMIN"] == 1) echo '<script type="text/javascript" src="js/admin.js"></script>' . "\n"; ?>
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
		<div id="background"><canvas id="background-canvas"></canavs></div>
		<div class="main">
			<div id="header">
				<div id="header-imprint">by Jan Scheiper 2012 | <a style="color: #EEE;" target="_blank" href="http://api.jangxx.com/info/imprint">Impressum</a></div>
				<div id="header-logininfo"><b>
					<a href="javascript:void(0)" onclick="load_page(<?php echo $_SESSION["SESS_ID"];?>)"><?php echo $_SESSION["SESS_USERNAME"]; ?></a></b> | <!--
					--><a href="api/logout.php">ausloggen</a> | <!--
					--><a href="javascript:void(0)" onclick="openDescription()">Steckbrief</a> | <!--
					--><a href="javascript:void(0)" onclick="openQuotes()">Zitate</a> | <!--
					--><a href="javascript:void(0)" onclick="openElections()">Wahlen</a> | <!--
					--><a href="javascript:void(0)" onclick="openSettings()">Einstellungen</a>
					<?php if ($_SESSION["SESS_ADMIN"] == 1) { echo ' | <a href="javascript:void(0)" onclick="openAdminPage()">Administation</a>'; }?>
				</div>
				<div id="header-image" onclick="load_startpage()"><div id="header-image-a"></div><div id="header-image-ak"></div></div>	
			</div>
			<div id="pane-container">
				<script type="text/javascript">resize();</script>
				<div id="nameslist">
					<div class="name-entry" id="name-search-entry">
						<input type="text" placeholder="Suchen..." class="input-field" id="name-search-entry-input">
					</div>
					<div id="entry-container"></div>
					<div id="nameslist-spinner" class="spinner-container">
						<div class="spinner_1"></div>
					</div>
				</div>
				<div id="middle-pane">
					<div id="middle-header-container"><div id="middle-header-pic"></div><div id="middle-header"></div></div>
					<div id="polls-area"></div>
					<div id="middle-commentsection"></div>
				</div>
			</div>
			
		</div>
<!--TEMPLATES-->
<?php
//POPUP
include 'templates/popup.php'; 
echo "\n";
//SETTINGS
include 'templates/settings.php'; 
echo "\n";
//ELECTIONS
include 'templates/elections.php'; 
echo "\n";
//DESCRIPTION
include 'templates/description.php';
echo "\n";
//QUOTES
include 'templates/quotes.php';
echo "\n";
//ADMIN
if ($_SESSION["SESS_ADMIN"] == 1) {
	include 'templates/admin.php'; 
	echo "\n";
} 
?>
<!--TEMPLATES END-->
	</body>
</html>