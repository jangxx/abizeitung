<?php
require "constants.php";

$lb = "<br/>\n";

$query = 'SELECT * FROM comments WHERE deleted=0';
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
$g = 0; $h = 0; $nh = 0;
while ($row = mysqli_fetch_array($result)) {
	$g++;
	if ($row["hidden"] == "1") {
		$h++;
	} else {
		$nh++;
	}
}
echo "Kommentare gesamt: " . $g . $lb;
echo "davon versteckt: " . $h . $lb;
echo "davon &Ouml;ffentlich: " . $nh . $lb;

$query = 'SELECT * FROM users WHERE hidden=0 ORDER BY lastname ASC';
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
$users = array();
while ($row = mysqli_fetch_array($result)) {
	$users[$row["id"]]["firstname"] = $row["firstname"];
	$users[$row["id"]]["lastname"] = $row["lastname"];
	$users[$row["id"]]["fullname"] = $row["firstname"] . " " . $row["lastname"];
}

$results = array();

$query = 'SELECT * FROM comments WHERE deleted=0';
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
$avg_add = 0;
while ($row = mysqli_fetch_array($result)) {
	if(!array_key_exists($row["to_id"], $results)) $results[$row["to_id"]] = 0;
	$results[$row["to_id"]]++;
	$avg_add++;
}
arsort($results);

echo '<br/><div style="height: 500px; width: 500px; overflow:scroll;">';
$i = 1;
foreach($results as $id=>$count) {
	echo "#" . $i . " " . utf8_decode($users[$id]["fullname"]) . " (" . $count . ")<br/>";
	$i++;
}
echo "</div><br/>";
echo "Durchschnitt: " . round($avg_add / count($users),2);
?>