<?php
session_start();
header("Content-Type: application/x-javascript");
?>
try { if (top != window) top.location.replace(location.href);
} catch(ignore) {}

var dC = {
"user":"<?php echo $_SESSION['username']; ?>",
"players":"",
"dcrate":0.15,
"mapx":0,
"playerInfo":[]
}

var map, canvas, overlay, maptype, arenainfo, mapv;
var playerPos, playerDir, playerVelY, playerPosZ, playerScore, playerKills, playerDeaths, playerStatus;
var key = [0,0,0,0,0];
var mapDim = [400,300];
var radarDim = [80,80];
var face = [];
var arena = [];
var pi = Math.PI;

var total = 0;
var samples = 200;
var xOff = 0;
var yOff = 0;
var jumpCycle = 0;

Number.prototype.range = function() { return ((this + (2 * pi)) % (2 * pi)); }
Number.prototype.roundC = function() { return (Math.round(this * 100) / 100); }

switch(dC.mapx) {
case 1:

arenainfo = [7,9,(pi / 2),0];
arena[0] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[1] = [1,0,0,0,0,0,0,0,0,0,0,0,0,1];
arena[2] = [1,0,1,1,1,1,1,1,1,1,1,1,0,1];
arena[3] = [1,0,1,0,0,0,0,0,0,0,0,1,0,1];
arena[4] = [1,0,1,0,0,1,0,1,1,1,0,1,0,1];
arena[5] = [1,0,1,0,1,0,0,0,0,1,0,0,0,1];
arena[6] = [1,0,1,0,0,0,0,1,0,1,0,1,0,1];
arena[7] = [1,0,1,0,1,1,0,0,0,0,0,1,0,1];
arena[8] = [1,0,0,0,0,1,0,1,1,1,0,1,0,1];
arena[9] = [1,0,1,1,0,1,0,0,0,1,0,1,0,1];
arena[10] = [1,0,1,0,0,1,0,1,0,0,0,1,0,1];
arena[11] = [1,0,1,1,1,1,1,1,1,1,1,1,0,1];
arena[12] = [1,0,0,0,0,0,0,0,0,0,0,0,0,1];
arena[13] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1];
mapv = [86, (86 / (arena.length + 1))]; // 6.14

break;
case 2:

arenainfo = [51,30.5,1];
arena[0] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[1] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[2] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[3] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,1,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[4] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[5] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,1,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[6] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[7] = [1,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[8] = [1,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[9] = [1,1,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[10] = [1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[11] = [1,1,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[12] = [1,1,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[13] = [1,1,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[14] = [1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[15] = [1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[16] = [1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[17] = [1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[18] = [1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[19] = [1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[20] = [1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[21] = [1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[22] = [1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[23] = [1,1,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[24] = [1,1,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[25] = [1,1,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[26] = [1,1,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1];
arena[27] = [1,1,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1];
arena[28] = [1,1,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1];
arena[29] = [1,1,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1];
arena[30] = [1,1,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,1,1,1,1,1,1,0,0,0,0,0,1,1,1,1,1,1,1,1,1];
arena[31] = [1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1,0,0,0,1,1,0,1,1,1,1,1,1,1];
arena[32] = [1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,0,0,0,1,0,0,0,0,0,0,0,0,1];
arena[33] = [1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1];
arena[34] = [1,0,0,0,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,0,0,0,1,0,0,0,0,0,0,0,0,1];
arena[35] = [1,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,0,1,1,1,1,1,0,1];
arena[36] = [1,0,0,0,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[37] = [1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[38] = [1,1,1,1,1,0,0,0,0,0,1,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[39] = [1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[40] = [1,1,1,1,1,0,0,0,0,0,1,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[41] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[42] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[43] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[44] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[45] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[46] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[47] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[48] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[49] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[50] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[51] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
arena[52] = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
mapv = [106, (106 / (arena.length + 1))]; // 2

break;
case 3:

arenainfo = [8,14,1];
arena[0] = [0,0,0,0,0,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
arena[1] = [0,0,0,0,0,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
arena[2] = [0,0,0,0,0,1,1,0,0,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0];
arena[3] = [0,0,0,0,0,1,1,0,0,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0];
arena[4] = [1,1,1,0,0,1,1,0,0,0,1,1,1,1,0,0,0,1,1,1,1,1,1,1,1,1];
arena[5] = [1,1,1,0,0,1,1,0,0,0,0,1,1,0,0,0,0,0,1,1,1,1,1,1,1,1];
arena[6] = [1,1,1,0,0,1,1,1,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1];
arena[7] = [1,1,1,0,0,1,1,1,1,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1];
arena[8] = [1,1,1,0,1,1,1,1,1,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1];
arena[9] = [1,1,1,0,0,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1];
arena[10] = [1,1,1,0,0,0,1,1,1,0,0,0,0,0,0,1,1,1,1,1,1,1,1,0,1,1];
arena[11] = [1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,0,1,1];
arena[12] = [1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,1,1];
arena[13] = [1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,1,1];
arena[14] = [1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0,0];
arena[15] = [1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1,1,1,1,0,0,1,1,1,1];
arena[16] = [1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1,1,1,0,0,1,1,1,0,0];
arena[17] = [1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1,1,0,0,1,1,1,0,0,0];
arena[18] = [1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0];
arena[19] = [1,1,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,1,1,1,0,0];
arena[20] = [1,1,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,1,1,1,0];
arena[21] = [0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,1,1,0];
arena[22] = [0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0,0,0];
/*
arena[23] = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
arena[24] = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
arena[25] = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
arena[26] = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
*/
mapv = [46, (46 / (arena.length + 1))]; // 2

break;
case 0:
default:

arenainfo = [4,4,0.4]; // [4,4,0.4,0,1,0,0,0,1];
arena[0] = [1,1,1,1,1,1,1,1,1,1];
arena[1] = [1,0,0,0,0,0,0,0,0,1];
arena[2] = [1,0,0,1,0,1,1,1,0,1];
arena[3] = [1,0,1,0,0,0,0,1,0,1];
arena[4] = [1,0,0,0,0,1,0,1,0,1];
arena[5] = [1,0,1,1,0,0,0,0,0,1];
arena[6] = [1,0,0,1,0,1,1,1,0,1];
arena[7] = [1,1,0,1,0,0,0,1,0,1];
arena[8] = [1,0,0,1,0,1,0,0,0,1];
arena[9] = [1,1,1,1,1,1,1,1,1,1];
mapv = [80,((radarDim[0] * 2) / (arena.length))]; // 8
// mapv = [80,((radarDim[0] * 2) / (arena.length))]; // 8
// mapv = 8;

break;
}

dC.playerInfo = [arenainfo[0],arenainfo[1],arenainfo[2],0,1,0,0,0,1];
playerPos = [dC.playerInfo[0], dC.playerInfo[1]];
playerDir = dC.playerInfo[2];
playerVelY = dC.playerInfo[3];
playerPosZ = dC.playerInfo[4];
playerScore = dC.playerInfo[5];
playerKills = dC.playerInfo[6];
playerDeaths = dC.playerInfo[7];
playerStatus = dC.playerInfo[8];

function wallDistance(theta) {
	var data = []; face = [];
	var x = playerPos[0], y = playerPos[1];
	var deltaX, deltaY;
	var distX, distY;
	var stepX, stepY;
	var mapX, mapY;
	var atX = Math.floor(x), atY = Math.floor(y);
	var thisRow = -1;
	var thisSide = -1;
	var lastHeight = 0;

	for (var i = 0; i < samples; i++) {
		theta += (pi / (3 * samples)) + (2 * pi);
		theta %= (2 * pi);
		mapX = atX, mapY = atY;
		deltaX = (1 / Math.cos(theta));
		deltaY = (1 / Math.sin(theta));

		if (deltaX > 0) {
			stepX = 1;
			distX = (mapX + 1 - x) * deltaX;
		} else {
			stepX = -1;
			distX = (x - mapX) * (deltaX *= -1);
		}

		if (deltaY > 0) {
			stepY = 1;
			distY = (mapY + 1 - y) * deltaY;
		} else {
			stepY = -1;
			distY = (y - mapY) * (deltaY *= -1);
		}

		for (var j = 0; j < 20; j++) {
		// for (var j = 0; j < ((arena.length + 1) * 2); j++) {
		// for (var j = 0; j < 20; j++) {
			if (distX < distY) {
				mapX += stepX;
				if (arena[mapX][mapY]) {
					if (thisRow != mapX || thisSide != 0) {
						if (i > 0) { data.push(i); data.push(lastHeight); }
						data.push(i);
						data.push(distX);
						thisSide = 0;
						thisRow = mapX;
						face.push(1 + stepX);
					}
					lastHeight = distX;
					break;
				}
				distX += deltaX;
			} else {
				mapY += stepY;
				if (arena[mapX][mapY]) {
					if (thisRow != mapY || thisSide != 1) {
						if (i > 0) { data.push(i); data.push(lastHeight); }
						data.push(i);
						data.push(distY);
						thisSide = 1;
						thisRow = mapY;
						face.push(2 + stepY);
					}
					lastHeight = distY;
					break;
				}
				distY += deltaY;
			}
		}
	}

	data.push(i);
	data.push(lastHeight);
	return data;
}

function drawCanvas() {
	canvas.clearRect(0,0,400,300);
	// canvas.clearRect(0,0,mapDim[0],mapDim[1]);
	// canvas.clearRect(0,0,400,300);
	var theta = playerDir - (pi / 6);
	var wall = wallDistance(theta);

	map.beginPath();
	map.clearRect(0,0,80,80);
	// map.clearRect(0,0,radarDim[0],radarDim[1]);
	// map.clearRect(0,0,80,80);
	map.fillStyle = "#36c"; // 36c
	map.arc((playerPos[0] * 8), (playerPos[1] * 8), 3, 0, (2 * pi), true);
	// map.arc((playerPos[0] * 8), (playerPos[1] * 8), 3, 0, (2 * pi), true);
	map.fill();
	map.beginPath();
	map.moveTo((8 * playerPos[0]), (8 * playerPos[1]));
	// map.moveTo((8 * playerPos[0]), (8 * playerPos[1]));

	var linGrad;
	var tl, tr, bl, br;
	var theta1, theta2, fix1, fix2;

	for (var i = 0; i < wall.length; i += 4) {
		theta1 = playerDir - (pi / 6) + pi * wall[i] / (3 * samples);
		theta2 = playerDir - (pi / 6) + pi * wall[i + 2] / (3 * samples);
		fix1 = Math.cos(theta1 - playerDir);
		fix2 = Math.cos(theta2 - playerDir);

		var h = (2 - playerPosZ);
		var wallH1 = (100 / (wall[i + 1] * fix1));
		var wallH2 = (100 / (wall[i + 3] * fix2));

		tl = [(wall[i] * 2), 150 - (wallH1 * h)];
		tr = [(wall[i + 2] * 2), 150 - (wallH2 * h)];
		br = [(wall[i + 2] * 2), tr[1] + (wallH2 * 2)];
		bl = [(wall[i] * 2), tl[1] + (wallH1 * 2)];

		var shade1 = Math.floor((wallH1 * 2) + 20); if (shade1 > 255) shade1 = 255;
		var shade2 = Math.floor((wallH2 * 2) + 20); if (shade2 > 255) shade2 = 255;

		linGrad = canvas.createLinearGradient(tl[0],0,tr[0],0);
		linGrad.addColorStop(0, 'rgba(' + (face[i / 4] % 2 == 0 ? shade1 : 0) + ',' + (face[i / 4] == 1 ? shade1 : 0) + ',' + (face[i / 4] == 2 ? 0 : shade1) + ',1.0)');
		linGrad.addColorStop(1, 'rgba(' + (face[i / 4] % 2 == 0 ? shade2 : 0) + ',' + (face[i / 4] == 1 ? shade2 : 0) + ',' + (face[i / 4] == 2 ? 0 : shade2) + ',1.0)');

		canvas.beginPath();
		canvas.moveTo(tl[0], tl[1]);
		canvas.lineTo(tr[0], tr[1]);
		canvas.lineTo(br[0], br[1]);
		canvas.lineTo(bl[0], bl[1]);
		canvas.fillStyle = linGrad;
		canvas.fill();

		map.lineTo((playerPos[0] * 8) + Math.cos(theta1) * (wall[i + 1]) * 8, (playerPos[1] * 8) + Math.sin(theta1) * (wall[i + 1]) * 8);
		map.lineTo((playerPos[0] * 8) + Math.cos(theta2) * (wall[i + 3]) * 8, (playerPos[1] * 8) + Math.sin(theta2) * (wall[i + 3]) * 8);
	}

	map.fillStyle = "#f00";
	map.fill();
	map.beginPath();
	map.fillStyle = "#36c";

	if ((dC.players != '') && (dC.players.indexOf('|') != "-1")) {
		$.each(dC.players.split('|'), function(index, value) {
			var playerinfo = value.split(',');
			if (playerinfo[0] != dC.user) {
				map.arc((playerinfo[1] * 8), (playerinfo[2] * 8), 3, 0, (2 * pi), true);
				map.fill();
			}
		});
	}
}

function nearWall(x,y) {
	var xx,yy;
	if (isNaN(x)) x = playerPos[0];
	if (isNaN(y)) y = playerPos[1];

	for (var i = -0.1; i <= 0.1; i += 0.2) {
		xx = Math.floor(x + i);

		for (var j = -0.1; j <= 0.1; j += 0.2) {
			yy = Math.floor(y + j);
			if (arena[xx][yy]) return true;
		}
	}

	return false;
}

function wobbleGun() {
	var mag = playerVelY;
	xOff = (10 + Math.cos(total / 6.23) * mag * 90);
	yOff = (10 + Math.cos(total / 5) * mag * 90);
	overlay.style.backgroundPosition = xOff + "px " + yOff + "px";
}

var audio = window.Audio && new Audio("shoot.wav");
// var audio2 = window.Audio && new Audio("shoot.wav");
// var audio3 = window.Audio && new Audio("shoot.wav");

function shoot() {
	if (audio) { audio && audio.play(); }
	canvas.save();
	canvas.strokeStyle = "#ff0";
	canvas.beginPath();
	canvas.moveTo((190 + xOff), (140 + yOff));
	canvas.lineTo((250 + xOff), (200 + yOff));
	canvas.closePath();
	canvas.stroke();
	canvas.restore();
	setTimeout('drawCanvas()', 100);

	// dC.playerInfo;
	var accurate = false;
	var playerX = dC.playerInfo[0];
	var playerY = dC.playerInfo[1];
	var newX = playerPos[0] + (Math.cos(playerDir) * playerVelY);
	var newY = playerPos[1] + (Math.sin(playerDir) * playerVelY);
	var diffX = (Math.cos(playerDir) * playerVelY);
	var diffY = (Math.sin(playerDir) * playerVelY);
	var ytarget = 0;
	
	if ((dC.players != '') && (dC.players.indexOf('|') != "-1")) {
		$.each(dC.players.split('|'), function(index, value) {
			var playerinfo = value.split(',');
			for (y = playerY; y > (playerY - ytarget); y += diffY) {
				
			}
		});
	}

	if (accurate) {
		$.ajax({
			url: 'load.php',
			data: 'id=game&action=update&data=' + dC.playerInfo.toString(),
			type: 'get',
			success: function (data) {
				dC.playerInfo[4] += 150;
				dC.playerInfo[5]++;
				alert("accurate");
			}
		});
	}
}

function update() {
	total++;
	var change = false;

	if (jumpCycle) {
		jumpCycle--;
		change = true;
		playerPosZ = 1 + jumpCycle * (20 - jumpCycle) / 110;
	} else if (key[4]) jumpCycle = 20;

	if (key[0]) {
		if (!key[1]) {
			playerDir -= dC.dcrate; // left
			change = true;
		}
	} else if (key[1]) {
		playerDir += dC.dcrate; // right
		change = true;
	}

	if (change) {
		playerDir += (2 * pi);
		playerDir %= (2 * pi);
		document.getElementById("sky").style.backgroundPosition = Math.floor(1 - playerDir / (2 * pi) * 2400) + "px 0";
	}

	if (key[2] && !key[3]) {
		if (playerVelY < 0.1) playerVelY += 0.02;
	} else if (key[3] && !key[2]) {
		if (playerVelY > -0.1) playerVelY -= 0.02;
	} else {
		if (playerVelY < -0.02) playerVelY += 0.015;
		else if (playerVelY > 0.02) playerVelY -= 0.015;
		else playerVelY = 0;
	}

	if (playerVelY != 0) {
		var oldX = oldX2 = playerPos[0];
		var oldY = playerPos[1];
		var newX = oldX + (Math.cos(playerDir) * playerVelY);
		var newY = oldY + (Math.sin(playerDir) * playerVelY);
		if (!nearWall(newX, oldY)) { playerPos[0] = newX; oldX = newX; change = true; }
		if (!nearWall(oldX, newY)) { playerPos[1] = newY; change = true; }
	} else {
		var oldX = oldX2 = newX = playerPos[0];
		var oldY = newY = playerPos[1];
	}

	if (playerVelY) wobbleGun();
	if (change) drawCanvas();
	if (change) {
		dC.playerInfo = [newX,newY,playerDir,playerVelY,playerPosZ,playerScore,playerKills,playerDeaths,playerStatus];
	}
}

function changeKey(which, to) {
	switch (which) {
		case 65: case 37: key[0] = to; break; // left
		case 87: case 38: key[2] = to; break; // up
		case 68: case 39: key[1] = to; break; // right
		case 83: case 40: key[3] = to; break;// down
		case 32: key[4] = to; break; // space bar;
		case 17: key[5] = to; break; // ctrl
		case 66: if (to) { shoot() } break; // b
	}
}

document.onkeydown = function(e) { changeKey((e || window.event).keyCode, 1); }
document.onkeyup = function(e) { changeKey((e || window.event).keyCode, 0); }
document.onmousedown = function() { changeKey(66, 1); }
document.onmouseup = function() { changeKey(66, 0); }

function initUnderMap() {
	var underMap = document.getElementById("underMap").getContext("2d");
	underMap.fillStyle = "#fff"; // f00
	underMap.fillRect(0,0,200,200);
	// underMap.fillRect(0,0,(radarDim[0] * 2),(radarDim[1] * 2));
	// underMap.fillRect(0,0,200,200);
	underMap.fillStyle = "#444";

	for (var i = 0; i < arena.length; i++) {
		for (var j = 0; j < arena[i].length; j++) if (arena[i][j]) underMap.fillRect((i * 8), (j * 8), 8, 8);
		// for (var j = 0; j < arena[i].length; j++) if (arena[i][j]) underMap.fillRect((i * mapv[1]), (j * mapv[1]), mapv[1], mapv[1]);
		// for (var j = 0; j < arena[i].length; j++) if (arena[i][j]) underMap.fillRect((i * 8), (j * 8), 8, 8);
	}
}

function updatePlayers() {
	$.ajax({
		url: 'load.php',
		data: 'id=game&action=update&data=' + dC.playerInfo.toString(),
		type: 'get',
		success: function (data) {
			if (data == dC.players) return; else drawCanvas();
			if ((data != "") && (data != "x")) dC.players = data;
			else if (data == "x") var error = false;
			if (!error) {
				$("div#players").html('');
				if (dC.players != '') {
					if (dC.players.indexOf('|') != "-1") {
						$.each(dC.players.split('|'), function(index, value) {
							var playerinfo = value.split(',');
							var player = [
							'<div id="' + playerinfo[0] + '" class="player">',
							'<div class="name">', playerinfo[0], '</div>',
							'<div class="x">X ', playerinfo[1], '</div>',
							'<div class="y">Y ', playerinfo[2], '</div>',
							'<div class="dir">D ', playerinfo[3], '</div>',
							'<div class="v">V ', playerinfo[4], '</div>',
							'<div class="z">Z ', playerinfo[5], '</div>',
							'<div class="score">Score ', playerinfo[6], '</div>',
							'<div class="kills">Kills ', playerinfo[7], '</div>',
							'<div class="deaths">Deaths ', playerinfo[8], '</div>',
							'<div class="status">Status ', playerinfo[9], '</div>',
							'</div>'
							].join('');
							$("div#players").append(player);
						});
					} else {
						var playerinfo = dC.players.split(',');
						var player = [
						'<div id="' + playerinfo[0] + '" class="player">',
						'<div class="name">', playerinfo[0], '</div>',
						'<div class="x">X ', playerinfo[1], '</div>',
						'<div class="y">Y ', playerinfo[2], '</div>',
						'<div class="dir">D ', playerinfo[3], '</div>',
						'<div class="v">V ', playerinfo[4], '</div>',
						'<div class="z">Z ', playerinfo[5], '</div>',
						'<div class="score">Score ', playerinfo[6], '</div>',
						'<div class="kills">Kills ', playerinfo[7], '</div>',
						'<div class="deaths">Deaths ', playerinfo[8], '</div>',
						'<div class="status">Status ', playerinfo[9], '</div>',
						'</div>'
						].join('');
						$("div#players").append(player);
					}
				}
			}
		}
	});
}

$(document).ready(function() {
	var ele = document.getElementById("map");
	if (!ele.getContext) {
		alert('An error occured creating a Canvas 2D context. This may be because you are using an old browser, if not please contact me and I\'ll see if I can fix the error.');
		return;
	}
	map = ele.getContext("2d");
	canvas = document.getElementById("canvas").getContext("2d");
	overlay = document.getElementById("overlay");
	document.getElementById("sky").style.backgroundPosition = Math.floor(-playerDir / (2 * pi) * 2400) + "px 0";
	drawCanvas();
	initUnderMap();
	setInterval(update, 35);
	setInterval(updatePlayers, 350);
});