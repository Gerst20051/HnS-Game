<?php
session_start();
chdir("/xampp/HomenetSpaces/hnsdesktop/");
include ("db.inc.php");
include ("login.inc.php");

$query = 'SELECT * FROM hns_desktop u JOIN info i ON u.user_id = i.user_id WHERE u.user_id = "' . mysql_real_escape_string($_SESSION['user_id'], $db) . '"';
$result = mysql_query($query, $db) or die(mysql_error($db));

if (mysql_num_rows($result) > 0) {
$row = mysql_fetch_assoc($result);
extract($row);
mysql_free_result($result);
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
<title>3D Canvas Shooter!</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1, windows-1252" />
<meta name="content-script-type" content="text/javascript" />
<meta name="content-style-type" content="text/css" />
<meta name="author" content="Homenet Spaces Andrew Gerst" />
<meta name="copyright" content="HnS Desktop" />
<meta name="keywords" content="Homenet, Spaces, HnS, OS, Web, Desktop, The, Place, To, Be, Creative, Andrew, Gerst, Free, Profile, Profiles, Apps, Applications" />
<meta name="description" content="Welcome to Homenet Spaces OS | This is the place to be creative! Feel free to add yourself to our wonderful community by registering! HnS Desktop" />
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="javascript.php"></script>
<link rel="stylesheet" type="text/css" href="css.php" media="all">
<base target="_top" />
</head>

<body scroll="no">
<!-- Begin page content -->
<div id="main">
<h2>3D Canvas Shooter</h2>
<div id="app">
<canvas id="underMap"></canvas>
<canvas id="map"></canvas>
<div id="holder">
<div id="sky"></div>
<div id="floor"></div>
<canvas id="canvas"></canvas>
<div id="overlay"></div>
</div>
</div>
<div id="info">
<h2>Game Info</h2>
<div id="players">
<h3>Players</h3>
</div>
</div>
</div>
<!-- End page content -->
</body>

</html>
<?php mysql_close($db); ?>