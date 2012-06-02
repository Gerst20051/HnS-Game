<?php
session_start();
header("Content-Type: text/css");
?>
/*
body { cursor: url(blank.gif); }
IE by using cursor: crosshair;
*/

div#main {
margin: 0 auto;
width: 780px;
}

div#app {
margin-top: 15px;
width: 780px;
}

div#holder {
border: 2px solid #333;
clear: both;
height: 300px;
left: 100px;
position: relative;
width: 400px;
}

div#sky {
background-color: #ccd;
background-image: url(sky.jpg);
height: 150px;
left: 0;
position: absolute;
top: 0;
width: 400px;
}

div#floor {
background-color: #565;
background-image: url(floor.png);
height: 150px;
left: 0;
position: absolute;
top: 150px;
width: 400px;
}

canvas#canvas {
left: 0;
position: absolute;
top: 0;
}

div#overlay {
background-image: url(overlay.gif);
height: 300px;
left: 0;
position: absolute;
top: 0;
width: 400px;
}

canvas#map, canvas#underMap {
position: absolute;
}

div#players {

}

div#players div.player {
clear: right;
float: left;
margin: 4px;
width: 225px;
}

div#players div.player div.name {
font-size: 12pt;
font-weight: bold;
}