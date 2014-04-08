<?php
fwrite(STDOUT, "abizeitung Installer\n");
fwrite(STDOUT, "by Jan Scheiper (c) 2014\n");
fwrite(STDOUT, "=========================\n\n");

fwrite(STDOUT, "Enter MySQL server address: ");
$ms_addr = trim(fgets(STDIN));
fwrite(STDOUT, "Enter MySQL server username (needs write access): ");
$ms_user = trim(fgets(STDIN));
fwrite(STDOUT, "Enter MySQL server password: ");
$ms_pw = trim(fgets(STDIN));
$ms_db = "abizeitung";

$mysqli = new mysqli($ms_addr, $ms_user, $ms_pw);

function dp($name, $query) {
	return array("name" => $name, "query" => $query);
}

$procedure = array(
	dp("Creating database '" . $ms_db . "'", 'CREATE DATABASE ' . $ms_db),
	dp("Switching to newly created database", "USE " . $ms_db),
	dp("Creating table 'comments'", "CREATE TABLE IF NOT EXISTS `comments` (
	  `id` int(6) NOT NULL AUTO_INCREMENT,
	  `from_id` int(4) NOT NULL,
	  `to_id` int(4) NOT NULL,
	  `hidden` int(1) NOT NULL DEFAULT '0',
	  `deleted` int(1) NOT NULL DEFAULT '0',
	  `importance` int(5) NOT NULL DEFAULT '0',
	  `text` varchar(2048) NOT NULL,
	  `date` datetime NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1;"),
	dp("Creating table 'description'", "CREATE TABLE IF NOT EXISTS `description` (
	  `id` int(4) NOT NULL AUTO_INCREMENT,
	  `user_id` int(4) NOT NULL,
	  `type` varchar(3) NOT NULL,
	  `value` varchar(2048) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=latin1;"),
	dp("Creating table 'gradecoupleresults'", "CREATE TABLE IF NOT EXISTS `gradecoupleresults` (
	  `id` int(6) NOT NULL AUTO_INCREMENT,
	  `from_id` int(6) NOT NULL,
	  `person1` int(6) NOT NULL,
	  `person2` int(6) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1;"),
	dp("Creating table 'misc'", "CREATE TABLE IF NOT EXISTS `misc` (
	  `id` int(3) NOT NULL AUTO_INCREMENT,
	  `key` varchar(64) NOT NULL,
	  `value` varchar(64) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1;"),
	dp("Inserting required settings into 'misc'", "INSERT INTO misc (`key`, `value`) VALUES('showhiddencomments', 'false')"),
	dp("Inserting required settings into 'misc'", "INSERT INTO misc (`key`, `value`) VALUES('deletecommentstome', 'false')"),
	dp("Inserting required settings into 'misc'", "INSERT INTO misc (`key`, `value`) VALUES('disablecomments', 'false')"),
	dp("Inserting required settings into 'misc'", "INSERT INTO misc (`key`, `value`) VALUES('disabledescription', 'false')"),
	dp("Inserting required settings into 'misc'", "INSERT INTO misc (`key`, `value`) VALUES('disablepolls', 'false')"),
	dp("Inserting required settings into 'misc'", "INSERT INTO misc (`key`, `value`) VALUES('disableelections', 'false')"),
	dp("Inserting required settings into 'misc'", "INSERT INTO misc (`key`, `value`) VALUES('disablecoupleelections', 'false')"),
	dp("Inserting required settings into 'misc'", "INSERT INTO misc (`key`, `value`) VALUES('disablecommentsort', 'false')"),
	dp("Inserting required settings into 'misc'", "INSERT INTO misc (`key`, `value`) VALUES('disablelogin', 'false')"),
	dp("Creating table 'news'", "CREATE TABLE IF NOT EXISTS `news` (
	  `id` int(4) NOT NULL AUTO_INCREMENT,
	  `text` varchar(1024) NOT NULL,
	  `date` datetime NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1;"),
	dp("Creating table 'pollresults'", "CREATE TABLE IF NOT EXISTS `pollresults` (
	  `id` int(4) NOT NULL AUTO_INCREMENT,
	  `poll` int(3) NOT NULL,
	  `user` int(4) NOT NULL,
	  `sending_user` int(4) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1;"),
	dp("Creating table 'polls'", "CREATE TABLE IF NOT EXISTS `polls` (
	  `id` int(3) NOT NULL AUTO_INCREMENT,
	  `name` varchar(1024) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1;"),
	dp("Creating table 'quotes'", "CREATE TABLE IF NOT EXISTS `quotes` (
	  `id` int(6) NOT NULL AUTO_INCREMENT,
	  `text` varchar(1024) NOT NULL,
	  `context` varchar(512) NOT NULL,
	  `user_id` int(6) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1;"),
	dp("Creating table 'teacherelections'", "CREATE TABLE IF NOT EXISTS `teacherelections` (
	  `id` int(4) NOT NULL AUTO_INCREMENT,
	  `name` varchar(64) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=latin1"),
	dp("Creating table 'teacherresults'", "CREATE TABLE IF NOT EXISTS `teacherresults` (
	  `id` int(4) NOT NULL AUTO_INCREMENT,
	  `from_id` int(4) NOT NULL,
	  `teacher_id` int(4) NOT NULL,
	  `elec_id` int(4) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1;"),
	dp("Creating table 'teachers'", "CREATE TABLE IF NOT EXISTS `teachers` (
	  `id` int(3) NOT NULL AUTO_INCREMENT,
	  `firstname` varchar(32) NOT NULL,
	  `lastname` varchar(32) NOT NULL,
	  `image` varchar(256) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1"),
	dp("Creating table 'users'", "CREATE TABLE IF NOT EXISTS `users` (
	  `id` int(4) NOT NULL AUTO_INCREMENT,
	  `username` varchar(64) NOT NULL,
	  `firstname` varchar(64) NOT NULL,
	  `lastname` varchar(64) NOT NULL,
	  `password` varchar(32) NOT NULL,
	  `default_password` varchar(32) NOT NULL DEFAULT '',
	  `pic` varchar(128) NOT NULL,
	  `hidden` int(1) NOT NULL DEFAULT '0',
	  `admin` int(1) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1;")
);

foreach($procedure as $step) {
	fwrite(STDOUT, $step["name"] . "...");
	$mysqli->query($step["query"]);
	if($mysqli->errno == 0) {
		fwrite(STDOUT, "done\n");
	} else {
		fwrite(STDOUT, "error\n");
		die("ERROR: " . $mysqli->error);
	}
}

fwrite(STDOUT, "\nEnter firstname: ");
$fn = trim(fgets(STDIN));
fwrite(STDOUT, "Enter lastname: ");
$ln = trim(fgets(STDIN));
fwrite(STDOUT, "Enter preferred password: ");
$pw = trim(fgets(STDIN));
fwrite(STDOUT, "Enter URL of profile picture or 'fb:<id>' for a facebook accountpicture [optional]: ");
$pic = trim(fgets(STDIN));
$pw = md5($pw);
$un = strtolower($fn) . '.' . strtolower($ln);
$ad = 1;

fwrite(STDOUT, "Adding root user...");
$stmt = $mysqli->prepare("INSERT INTO users (username, firstname, lastname, password, pic, admin) VALUES(?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssi", $un, $fn, $ln, $pw, $pic, $ad);
$stmt->execute();
if($stmt->errno != 0) die("ERROR: " . $stmt->error);
fwrite(STDOUT, "done\n\n");

$FILECONTENTS = '$con = $GLOBALS["___mysqli_ston"] = mysqli_connect($MYSQL_ADDRESS, $MYSQL_USERNAME, $MYSQL_PASSWORD, $MYSQL_DATABASE);

$query = "SELECT * FROM misc";
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
echo mysqli_error($con);
while ($row = mysqli_fetch_array($result)) {
	$SETTINGS[$row["key"]] = $row["value"];
	setcookie($row["key"], $row["value"]);
}
unset($query);
unset($result);
unset($row);
';

fwrite(STDOUT, "Writing settings to 'constants.php'...");

file_put_contents('constants.php', "<?php
\$MYSQL_DATABASE = '" . $ms_db . "';
\$MYSQL_USERNAME = '" . $ms_user . "';
\$MYSQL_PASSWORD = '" . $ms_pw . "';
\$MYSQL_ADDRESS = '" . $ms_addr . "';\n\n" . $FILECONTENTS . "?" . ">");

fwrite(STDOUT, "done\n");
fwrite(STDOUT, "Copying file to tools/constants.php...");
$cp = copy('constants.php', 'tools/constants.php');
if($cp) fwrite(STDOUT, "done\n");
else fwrite(STDOUT, "error\n");
fwrite(STDOUT, "Moving file to page/api/constants.php...");
$mv = rename('constants.php', 'page/api/constants.php');
if($mv) fwrite(STDOUT, "done\n");
else fwrite(STDOUT, "error\n");

fwrite(STDOUT, "Congratulations! The installation is complete!\n");
?>