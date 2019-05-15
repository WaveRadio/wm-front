<?php

function processEdit()
{
	$track_id = (int)$_POST['track-id'];
	if (!trackExists($track_id))
		json_respond(1, 'This track does not exist');

	$track_artist = trim($_POST['track-artist']);
	if (empty($track_artist) || mb_strlen($track_artist, 'UTF-8') > 64)
		json_respond(2, 'Bad track artist name');

	$track_title = trim($_POST['track-title']);
	if (empty($track_title) || mb_strlen($track_title, 'UTF-8') > 64)
		json_respond(3, 'Bad track title');

	editTrack($track_id, $track_artist, $track_title);
	
	json_respond(0, 'OK');
}

array_shift($route); // remove /library/
switch ($route[0])
{
	case 'edit' :
		processEdit();
		break;

	default:
		json_respond (-1, 'Bad API request');
}