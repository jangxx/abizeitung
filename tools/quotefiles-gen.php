<?php
if ($_SERVER["HTTPS"] != "on") {
header("location: " . "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]); exit(); }

require "constants.php";

session_start();
if ($_SESSION["SESS_ADMIN"] < 1) {
	echo "Bitte als Admin einloggen.";
	exit();
}

if(!file_exists('quotefiles')) mkdir('quotefiles');
if(!file_exists('quotefiles')) die('Cannot create directory "quotefiles"');

$users = array();
$query = 'SELECT * FROM users';
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
while ($row = mysqli_fetch_array($result)) {
	$users[$row["id"]] = utf8_decode($row["firstname"] . ' ' . $row["lastname"]);
}

if (isset($_GET["delete"])) {
	if (file_exists("quotefiles/" . $_GET["delete"]) && strpos(realpath("quotefiles/" . $_GET["delete"]), realpath(null)) !== false)
		unlink("quotefiles/" . $_GET["delete"]);
}

$FILEGEN = isset($_GET["from"], $_GET["to"]);
$quotes = array();

$query = 'SELECT * FROM quotes';
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
$quotecount = 0;
$i = 0;
while ($row = mysqli_fetch_array($result)) {
	if ($FILEGEN && $quotecount >= $_GET["from"] && $quotecount <= $_GET["to"]) {
		$quotes[$i]["text"] = utf8_decode(str_replace("<br/>" , "\r\n", str_replace("&rdquo;", '"' , str_replace("&lsquo;", "'", $row["text"]))));
		$quotes[$i]["context"] = utf8_decode(str_replace("<br/>" , "\r\n", str_replace("&rdquo;", '"' , str_replace("&lsquo;", "'", $row["context"]))));
		$i++;
	}
	$quotecount++;
}

if ($FILEGEN) {
	$filename = $_GET["from"] . "-" . ($_GET["from"] + $i - 1) . "-" . time() . ".txt";
	$hFile = fopen("quotefiles/" . $filename, "w");
	foreach($quotes as $quote) {
		fwrite($hFile, "==========" . "\r\n");
		fwrite($hFile, $quote["text"] . "\r\n");
		fwrite($hFile, "----------" . "\r\n");
		fwrite($hFile, $quote["context"] . "\r\n");
		fwrite($hFile, "==========" . "\r\n" . "\r\n");
	}
	fclose($hFile);
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Zitate Datei Generator</title>
		<style type="text/css">
			.big {
				font-size: 2em;
			}
			
			.b {
				font-weight: bold;
			}
			
			.exp {
				font-size: 1.5em;
			}
			
			body {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 1em;
				width: 600px;
			}
		</style>
		<script type="text/javascript">
			try {
				window.history.pushState("", window.pageTitle, location.pathname);
			} catch(ex) {}
			
			function updateto(evt) {
				if (evt.target.value/1 > evt.target.max/1) {
					evt.target.value = evt.target.max;
				}
				
				var toElem = document.getElementById("to-input");
				var toVal = toElem.value/1;
				if (evt.target.value >= toVal) {
					toElem.value = evt.target.value/1 + 1;
				}
			}
			
			function updatefrom(evt) {
				if (evt.target.value/1 > evt.target.max/1) {
					evt.target.value = evt.target.max;
				}
				
				var fromElem = document.getElementById("from-input");
				var fromVal = fromElem.value/1;
				if (evt.target.value <= fromVal) {
					fromElem.value = evt.target.value/1 - 1;
				}
			} 
		</script>
	</head>
	<body>
		<span class="big b">Zitat Exporter</span><br/><br/>
		Zitate gesamt: <?php echo $quotecount;?><br/>
		<form action="quotefiles-gen.php" method="get">
			Von: <input id="from-input" name="from" type="number" min="0" max="<?php echo $quotecount-1;?>" value="0" onchange="updateto(event)"><br/>
			Bis: <input id="to-input" name="to" type="number" min="1" max="<?php echo $quotecount;?>" value="1" onchange="updatefrom(event)"><br/>
			<input type="submit" value="Exportieren">
		</form><br/>
		<span class="exp">Erstellte Dateien:</span>
		<div id="exported-files">
			<?php
			$dir = opendir("quotefiles");
			$files = 0;
			while (($file = readdir($dir))) {
				if (strpos($file, '.') != 0) {
					$files++;
					$opts = explode("-", $file);
					echo "Von: " . $opts[0];
					echo " Bis: " . $opts[1];
					echo " Erstelldatum: " . date('d.m.Y H:i', filemtime("quotefiles/" . $file));
					echo ' <a target="_blank" href="' . "quotefiles/" . $file . '">Download</a>';
					echo ' <a href="?delete=' . $file . '">Löschen</a><br/>';
				}
			}
			if ($files == 0) echo 'Keine Dateien gefunden.';
			?>
		</div>
	</body>
</html>