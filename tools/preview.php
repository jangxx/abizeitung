<?php
if ($_SERVER["HTTPS"] != "on") {
header("location: " . "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]); exit(); }

require "constants.php";

session_start();
if ($_SESSION["SESS_ADMIN"] < 1) {
	echo "Bitte als Admin einloggen.";
	exit();
}

$query = "SELECT * FROM users WHERE hidden=0 ORDER BY lastname ASC";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
$users = array();
while ($row = mysqli_fetch_array($result)) {
	$users[$row["id"]] = $row;
}
$uif = array("de" => array(
	"nn" => "", //Nickname
	"dob" => "", //Day of birth
	"mob" => "", //month of birth
	"yob" => "", //year of birth
	"lm" => "", //lebensmotto
	"abm" => "", //was ich schon immer loswerde wollte
	"a1" => "", //Abifach 1
	"a2" => "", //Abifach 2
	"a3" => "", //Abifach 3
	"a4" => "", //Abifach 4
	"g89" => "", //G8/9
	"ld1" => "", //"Lieblings" description 1
	"l1" => "", //"Lieblings" 1
	"ld2" => "", //"Lieblings" description 2
	"l2" => "", //"Lieblings" 2
	"ld3" => "", //"Lieblings" description 3
	"l3" => "", //"Lieblings" 3
	"ld4" => "", //"Lieblings" description 4
	"l4" => "", //"Lieblings" 4
	"ld5" => "", //"Lieblings" description 5
	"l5" => "", //"Lieblings" 5
	));
$comments = array();
if (array_key_exists("id", $_GET) and is_numeric($_GET["id"])) {
	$id = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_GET["id"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$uif["ge"] = $users[$id];
	
	$query = "SELECT * FROM description WHERE user_id=" . $id;
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	while($row = mysqli_fetch_array($result)) {
		$uif["de"][$row["type"]] = $row["value"];
	}
	
	$query = "SELECT * FROM comments WHERE to_id=" . $id . " AND deleted=0 ORDER BY importance DESC";
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	while($row = mysqli_fetch_array($result)) {
		$comments[] = $row;
	}
}

function _8($string) {
	return utf8_decode($string);
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Seiten Vorschau</title>
		<style type="text/css">
			.vbig {
				font-size: 2.5em;
				text-decoration: underline;
			}
			
			.big {
				font-size: 2em;
			}
			
			.exp {
				font-size: 1.5em;
			}
			
			.b {
				font-weight: bold;
			}
			
			body {
				font-family: Arial, Helvetica, sans-serif;
			}
			
			.ohlh {
				line-height: 1.5em;
			}
		</style>
	</head>
	<body>
		<form action="preview.php" method="get">
			<select name="id">
			<?php
				foreach ($users as $id=>$data) {
					echo '<option value="' . $id . '"';
					if (array_key_exists("id", $_GET) and $id == $_GET["id"]) echo ' selected="true"';
					echo '>' . _8($data["firstname"]) . ' ' . _8($data["lastname"]) . '</option>' . "\n";
				}
			?>
			</select>
			<input type="submit" value="Anzeigen">
		</form>
		<?php if (!array_key_exists("id", $_GET)) {
			echo "</body>\n</html>";
			exit(); } ?>
		<span class="vbig b"><?php echo _8($uif["ge"]["firstname"] . ' ' . $uif["ge"]["lastname"]); ?></span><br/>
		<span class="big b"><?php echo _8($uif["de"]["nn"]); ?></span><br/>
		<span class="exp"><?php echo _8($uif["de"]["lm"]); ?></span><br/>
		<?php echo _8($uif["de"]["dob"] . '.' . $uif["de"]["mob"] . '.' . $uif["de"]["yob"]); ?><br/>
		<?php echo strtoupper($uif["de"]["g89"]); ?><br/>
		<br/>
		<span class="exp b">Abifächer:</span><br/>
		1) <?php echo _8($uif["de"]["a1"]); ?><br/>
		2) <?php echo _8($uif["de"]["a2"]); ?><br/>
		3) <?php echo _8($uif["de"]["a3"]); ?><br/>
		4) <?php echo _8($uif["de"]["a4"]); ?><br/>
		<br/>
		
		<span class="exp b">Zukunftspläne:</span><br/>
		<?php
		$futureplans = array();
		foreach($uif["de"] as $key=>$value) {
			if ($c = preg_match_all ("/(f)(\\d+)/is", $key, $matches)) {
				$futureplans[$matches[2][0]] = $value;
			}
		}
		foreach($futureplans as $plan) {
			echo '- ' . _8($plan) . '<br/>' . "\n";
		}
		?>
		<br/>
		<span class="exp b">Präferenzen:</span><br/>
		<?php
		for ($i = 1; $i <= 5; $i++) {
			$str = _8($uif["de"]["ld" . $i]);
			if (strpos($str,"Lieblings") === false && strlen($str) > 1) echo 'Lieblings';
			echo $str . ': ';
			echo  _8($uif["de"]["l" . $i]) . '<br/>' . "\n"; 
		} ?><br/>
		<br/>
		<span class="exp b">Was ich schon immer loswerden wollte</span><br/>
		<?php echo _8($uif["de"]["abm"]); ?><br/>
		<br/>
		<span class="exp b">Kommentare</span><br/>
		<?php foreach($comments as $comment) {
			echo '<span class="ohlh">- ' . _8($comment["text"]) . "</span><br/>\n";
		} ?>
	</body>
</html>