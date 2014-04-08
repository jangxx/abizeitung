<?php
header('Content-Type:text/html; charset=UTF-8');
require "constants.php";
$Base_URL = "http://www.arnoldinum.de/arnoldinum/seite-fuer-lexikon-lehrer.php?seite_id=108&lexg_id=";
$ALPHABET = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

$Teachers = array();

for ($i = 1; $i <= /*2*/strlen($ALPHABET); $i++) {
	$filedata = file_get_contents($Base_URL . $i);
	if ($filedata == false) continue;
	$Teachers[$ALPHABET{$i - 1}] = getTeachersFromPageData($filedata);
}

$print = (array_key_exists("print", $_GET)) ? $_GET["print"] : "plain";
switch ($print) {
	case "html":
		echo "<html><head><title>Lehrerliste</title></head><body>";
		echo "<h1>Lehrerliste</h1>";
		foreach($Teachers as $index=>$inteacher) {
			echo "<b>" . $index . "</b><br/>";
			foreach ($inteacher as $teacher) {
				echo '<a href="' . $teacher["image"] . '">' . $teacher["firstname"] . " " . $teacher["lastname"] . "</a><br/>\n";
			}
		}
		echo "</body></html>";
		break;
	case "plain":
		foreach($Teachers as $index=>$inteacher) {
			foreach ($inteacher as $teacher) {
				echo $teacher["firstname"] . " " . $teacher["lastname"] . "\n";
				echo $teacher["image"] . "\n";
			}
		}
		break;
	case "mysql":
		foreach($Teachers as $index=>$inteacher) {
			foreach ($inteacher as $teacher) {
				$query = 'INSERT INTO teachers (firstname, lastname, image) VALUES("' . $teacher["firstname"] . '", "' . $teacher["lastname"] . '", "' . $teacher["image"] . '")';
				$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
				if ($result != false) {
					echo "Success<br>\n";
				} else {
					echo "Error query('" . $query . "')<br>\n";
				}
			}
		}
		break;
	case "json":
		echo json_encode($Teachers);
		break;
}
exit();

function getTeachersFromPageData($pagedata) {
	$return = array();
	$pos = 0;
	$i = 0;
	$spos = strpos($pagedata, "farbea", $pos);
	while ($spos !== false) {
		$npos = strpos($pagedata, "boxtitel", $spos) + strlen("boxtitel") + 2;
		$nepos = strpos($pagedata, "<", $npos);
		$name = substr($pagedata, $npos, $nepos - $npos);
		list($lastname, $firstname, $position) = explode(", ", $name);
		
		$ppos = strpos($pagedata, 'src="', $spos) + strlen('src="');
		$pepos = strpos($pagedata, '"', $ppos);
		$pic = str_replace('..', "http://www.arnoldinum.de", substr( $pagedata, $ppos, $pepos - $ppos));
		
		if (substr($position, -1) == ")") {
			$toreturn = array();
			$toreturn["firstname"] = utf8_encode($firstname);
			$toreturn["lastname"] = utf8_encode($lastname);
			$toreturn["image"] = $pic;
			$return[] = $toreturn;
		}
		
		$i++;
		$pos = $pepos;
		$spos = strpos($pagedata, "farbea", $pos);
	}
	return $return;
}
?>