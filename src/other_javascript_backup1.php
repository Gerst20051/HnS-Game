<?php
session_start();
header("Content-Type: application/x-javascript");
?>
/*
Here is one solution to overcome the single-channel limitation of the audio tag:
Use multiple rotating audio channels and assign new sounds to currently unused channels.
Click the links above rapidly to test this.
In the example above we use 10 channels (generated audio objects) and whenever the user clicks another sound to play, the script finds an inactive (and therefore unblocked) channel and then loads and plays the selected sound.
Each of the sounds is being preloaded with an audio html tag that is actually never used to play the sound - the preload="auto" property suggests to the browser to load all of the sounds when the page loads (this depends on available space and general user preferences in the browser), instead of when the sound is played for the first time through one of the generated audio channels.
The script checks each channel if it is done playing the previous sound.
There is an "ended" property for the audio object, but since that property is "false" when new objects are created (which is correct, but inconvenient),
I've decided to keep track of the expected end times for each sound channel instead.

<audio id="multiaudio1" src="audio/flute_c_long_01.wav" preload="auto"></audio>
<audio id="multiaudio2" src="audio/piano_chord.wav" preload="auto"></audio>
<audio id="multiaudio3" src="audio/synth_vox.wav" preload="auto"></audio>
<audio id="multiaudio4" src="audio/shimmer.wav" preload="auto"></audio>
<audio id="multiaudio5" src="audio/sweep.wav" preload="auto"></audio>
<a href="javascript:play_multi_sound('multiaudio1');">Flute</a><br />
<a href="javascript:play_multi_sound('multiaudio2');">Piano Chord</a><br />
<a href="javascript:play_multi_sound('multiaudio3');">Synth Vox</a><br />
<a href="javascript:play_multi_sound('multiaudio4');">Shimmer</a><br />
<a href="javascript:play_multi_sound('multiaudio5');">Sweep</a><br />

var channel_max = 10; // number of channels
audiochannels = new Array();

for (a = 0; a < channel_max; a++) { // prepare the channels
	audiochannels[a] = new Array();
	audiochannels[a]['channel'] = new Audio(); // create a new audio object
	audiochannels[a]['finished'] = -1; // expected end time for this channel
}

function play_multi_sound(s) {
	for (a = 0; a < audiochannels.length; a++) {
		thistime = new Date();

		if (audiochannels[a]['finished'] < thistime.getTime()) { // is this channel finished?
			audiochannels[a]['finished'] = thistime.getTime() + (document.getElementById(s).duration * 1000);
			audiochannels[a]['channel'].src = document.getElementById(s).src;
			audiochannels[a]['channel'].load();
			audiochannels[a]['channel'].play();
			break;
		}
	}
}

This solution can easily be incorporated into complex animations and games.
If all sounds are preloaded with audio tags, this function is able to play any sound at any moment, even multiple instances of the same sound effect.
The 10-channel limit in the example above is arbitrary and it will take some experimentation to find the true limits of how many parallel audio objects can be generated without performance issues.
*/