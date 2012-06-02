<?php
session_start();
chdir("/xampp/HomenetSpaces/hnsdesktop/");
define('MYSQL_HOST','localhost');
define('MYSQL_USER','root');
define('MYSQL_PASSWORD','comwiz05');

if (isset($_GET['id'])) {
$id = trim($_GET['id']);
if (isset($_SESSION['username'])) $username = $_SESSION['username']; else $username = "GUSER";
$userid = $_SESSION['user_id'];
if (isset($_GET['action'])) $action = trim($_GET['action']);
if (isset($_GET['data'])) $data = trim($_GET['data']);

switch ($id) {
case 'game':

switch ($action) {
case 'receive':

$db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or die ("<h2>Unable to connect to database Members. Check your connection parameters.</h2>");
mysql_select_db("members", $db) or die(mysql_error($db));
$timestamp = time();

$result = mysql_query("SELECT player, info FROM game", $db);
while ($row = mysql_fetch_array($result)) {
if (empty($playerslist)) $playerslist = $row['player'] . ',' . $row['info'];
else $playerslist .= '|' . $row['player'] . ',' . $row['info'];
}

echo $playerslist;
mysql_free_result($result);
mysql_close($db);

break;
case 'update':

$db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or die ("<h2>Unable to connect to database Members. Check your connection parameters.</h2>");
mysql_select_db("members", $db) or die(mysql_error($db));
$timestamp = time();
$timeout = ($timestamp - 30);

mysql_query('INSERT INTO game (timestamp, player, info) VALUES ("' . $timestamp . '", "' . $username . '", "' .  $data . '") ON DUPLICATE KEY UPDATE timestamp = "' . $timestamp . '", info = "' . $data . '"', $db) or die(mysql_error($db));
mysql_close($db);

break;
case 'delete';

$db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or die ("<h2>Unable to connect to database Members. Check your connection parameters.</h2>");
mysql_select_db("members", $db) or die(mysql_error($db));

$timestamp = time();
$timeout = ($timestamp - 30);

mysql_query("DELETE FROM game WHERE timestamp < $timeout", $db);
mysql_close($db);

break;
}

break;
}
}
?>