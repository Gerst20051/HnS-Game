<?php
session_start();
chdir("/xampp/HomenetSpaces/hnsdesktop/");

if (isset($_GET['id'])) {
$id = trim($_GET['id']);
if (isset($_SESSION['username'])) $username = $_SESSION['username']; else $username = "GUSER";
$userid = $_SESSION['user_id'];
if (isset($_GET['action'])) $action = trim($_GET['action']);
if (isset($_GET['data'])) $data = trim($_GET['data']);
$newplayerslist = '';
$timestamp = time();
$timeout = ($timestamp - 30);

switch ($id) {
case 'game':

switch ($action) {
case 'update':

define('MYSQL_HOST','localhost');
define('MYSQL_USER','root');
define('MYSQL_PASSWORD','');

$db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or die ("<h2>Unable to connect to database Members. Check your connection parameters.</h2>");
mysql_select_db("members", $db) or die(mysql_error($db));

/*
mysql_query("DELETE FROM users_online WHERE timestamp < $timeout", $db);

$query = 'SELECT * FROM login u JOIN info i ON u.user_id = i.user_id ORDER BY date_joined ASC';
$result = mysql_query($query, $db) or die(mysql_error($db));

$result = mysql_query('SELECT user_id FROM login');
$numrows = mysql_num_rows($result);

$uresult = mysql_query("SELECT DISTINCT username FROM users_online ORDER BY username ASC", $db) or die("Database SELECT Error");

echo $row['username'] . " | " . $fullname;
while ($onlineusers = mysql_fetch_array($uresult, MYSQL_ASSOC)) {
foreach ($onlineusers as $users) if ($row['username'] == $users) echo " is Online!";
};
echo "</a>";

if (mysql_num_rows($result) > 0) {
$row = mysql_fetch_assoc($result);
extract($row);
mysql_free_result($result);
}

$query = 'UPDATE info SET logins = ' . $logins . ' WHERE user_id = ' . $_SESSION['user_id'];
mysql_query($query, $db) or die(mysql_error());

$query = 'UPDATE login SET
last_login = "' . $last_login . '",
last_login_ip = "' . $ip . '"
WHERE
user_id = ' . $_SESSION['user_id'];
mysql_query($query, $db) or die(mysql_error());
*/

$result = mysql_query("SELECT player FROM game WHERE player = " . $username, $db);
if (mysql_num_rows($result) > 0) mysql_query('UPDATE game SET timestamp = ' . $timestamp . ', info = ' . $data . ' WHERE player = ' . $username, $db) or die(mysql_error());
else mysql_query('INSERT INTO game (timestamp, player, info) VALUES (' . $timestamp . ', ' . $username . ', ' .  $data . ')', $db) or die(mysql_error($db));
mysql_query("DELETE FROM game WHERE timestamp < $timeout", $db);

$result = mysql_query("SELECT player, info FROM game", $db);
$numrows = mysql_num_rows($result);

if ($numrows > 0) {
	$row = mysql_fetch_assoc($result);
	extract($row);
	mysql_free_result($result);
	
	while ($players = mysql_fetch_array($result, MYSQL_ASSOC)) {
		foreach ($players as $player) if ($username == $player) echo " is Online!";
	}
}

echo $playerslist;

mysql_close($db);

if (strlen(trim($playerslist)) > 0) {
	if (strpos($playerslist, "|") === true) {
		$playerlist = explode("|", $playerslist);
		foreach ($playerlist as $players) {
			$player = explode(",", $players);
			if ($username == $player[0]) {
				if (strlen(trim($newplayerslist)) == 0) $newplayerslist = $newdata; else $newplayerslist .=  "|$newdata";
			} else {
				if (strlen(trim($newplayerslist)) == 0) $newplayerslist = $players; else $newplayerslist .=  "|$players";
			}
		}
	} else {
		$player = explode(",", $playerslist);
		if ($username == $player[0]) $newplayerslist = $newdata;
		else $newplayerslist = "$playerslist|$newdata";
	}
} else $newplayerslist = $newdata;


/*
$file = "game/playerlist.txt";
$playerslist = file_get_contents($file);
if (strlen(trim($playerslist)) > 0) {
	if (strpos($playerslist, "|") === true) {
		$playerlist = explode("|", $playerslist);
		foreach ($playerlist as $players) {
			$player = explode(",", $players);
			if ($username == $player[0]) {
				if (strlen(trim($newplayerslist)) == 0) $newplayerslist = $newdata; else $newplayerslist .=  "|$newdata";
			} else {
				if (strlen(trim($newplayerslist)) == 0) $newplayerslist = $players; else $newplayerslist .=  "|$players";
			}
		}
	} else {
		$player = explode(",", $playerslist);
		if ($username == $player[0]) $newplayerslist = $newdata;
		else $newplayerslist = "$playerslist|$newdata";
	}
} else $newplayerslist = $newdata;
file_put_contents($file, $newplayerslist);
echo $newplayerslist;
*/

break;
}

break;
}
}
?>