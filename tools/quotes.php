<?php
if ($_SERVER["HTTPS"] != "on") {
header("location: " . "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]); exit(); }

require "constants.php";

session_start();
if ($_SESSION["SESS_ADMIN"] < 1) {
	echo "Bitte als Admin einloggen.";
	exit();
}
$con = ($GLOBALS["___mysqli_ston"] = mysqli_connect($MYSQL_ADDRESS, $MYSQL_USERNAME, $MYSQL_PASSWORD));
((bool)mysqli_query($con, "USE $MYSQL_DATABASE"));

$users = array();
$query = 'SELECT * FROM users';
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row = mysqli_fetch_array($result)) {
	$users[$row["id"]] = utf8_decode($row["firstname"] . ' ' . $row["lastname"]);
}

$query = 'SELECT * FROM quotes';
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Zitate</title>
		<style type="text/css">
			.quote {
				margin-top: 20px;
				border-style: solid;
				border-width: 1px;
				border-color: #111;
				padding: 3px;
				border-radius: 5px;
			}
			
			.quote:first-child {
				margin-top: 0px;
			}
			
			.context {
				margin-top: 5px;
				border-width: 1px;
				border-top-color: #BBB;
				border-top-style: solid;
				padding-left: 10px;
				font-size: 0.9em;
				border-radius: 5px;
			}
			
			.sender {
				font-size: 0.7em;
				font-style: italic;
			}
			
			body {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 1em;
				width: 600px;
			}
		</style>
	</head>
	<body>
		<?php
		$i = 0;
		while ($row = mysqli_fetch_array($result)) {
			$i++;
			echo '<div class="quote">';
			echo utf8_decode($row["text"]);
			echo '<div class="context">';
			echo utf8_decode($row["context"]);
			echo '</div></div><div class="sender">';
			echo $i . ') (' . $users[$row["user_id"]] . ')';
			echo '</div>'. "\n";
		}
		?>
		<br/>
		<div>
			Kommentare gesamt: <?php echo $i;?>
		</div>
	</body>
</html>