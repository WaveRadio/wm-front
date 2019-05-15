<?php

$station = getSelectedStation();

if (empty($station) || !db_stationExists($station)) {
	json_respond (1, 'Bad station tag', true);
}

$currentTrack = trim(file_get_contents(WM_DATA_DIR.'/runtime/tags/'.$station.'.tag'));

// This is used by frontend to detect track changes with less server load impact
if ($_GET['brief'] === '1') {
	json_respond(0, $currentTrack, true);
}

$trackMetadata = splitMetadata($currentTrack);

if (empty($trackMetadata))
	json_respond(1, Array('error' => 'Malformed metadata: no artist/title', 
						  'raw_title' => $currentTrack), true);

error_log(print_r($trackMetadata, true));

$artistMetadata = db_searchArtist($trackMetadata['artist']);

if (empty($artistMetadata))
	json_respond(2, Array('error' => 'Malformed metadata: no artist data in the database', 
						  'artist' => $trackMetadata['artist'],
					  	  'title' => $trackMetadata['title']), true);

$artistLinks = db_getArtistLinks($artistMetadata['artist_id']);

json_respond(0, Array('artist' => $artistMetadata['artist_name'],
					  'title' => $trackMetadata['title'],
					  'city' => $artistMetadata['artist_city_name'],
					  'links' => $artistLinks), true);