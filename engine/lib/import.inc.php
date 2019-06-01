<?php

function importTracks ($sourceStruct, $interactive) {

	$logString = "";

	$log = function($message) use (&$logString, $interactive) {
		if ($interactive) {
			echo ($message);
		} else {
			$logString .= $message;
		}
	};

	foreach ($sourceStruct as $artist) {

		$log ('Processing artist '. $artist['artist']."\n");

		$artistProfile = db_searchArtist($artist['artist']);

		$artistId = 0;
		if (empty($artistProfile)) {

			$log ("No artist with this name/alias found, creating new...\n");
			$artistId = db_addArtist($artist['artist']);

			if ($artistId === 0) {
				die ("ERROR: Could not add new artist to the database, exiting!\n");
			}
		} else {
			$artistId = (int)$artistProfile['artist_id'];
			$log ("This artist is already exists in our database, ID $artistId\n");
		}

		$log ("Processing links: getting links for current artist...\n");

		$artistLinks = db_getArtistLinks($artistId);

		if (empty($artistLinks)) {
			$log ("Artist has no links at all, OK, just adding everything we have\n");
		} else {
			$log ("WARNING: Removing all artist links to replace them with a new list!\n");
			db_removeAllArtistLinks($artistId);
		}

		if (!empty($artist['bandcamp'])) {
			db_addArtistLink($artistId, $artist['bandcamp']);
		}

		if (!empty($artist['vk'])) {
			db_addArtistLink($artistId, $artist['vk']);
		}

		$log ("Links are processed, processing city...\n");

		if (empty($artist['city'])) {
			$log ("No city specified, passing this away\n");
		} else {
			$log ('The specified city is '.$artist['city'].", checking if this city exists in our DB...\n");
			$cityId = 0;

			$cityData = db_searchArtistCity($artist['city']);

			if (empty($cityData)) {
				$log ("This city isn't known yet, adding\n");

				$cityId = (int)db_addArtistCity($artist['city']);

				if ($cityId === 0) {
					die ("ERROR: Could not add new city to the database, exiting!\n");
				}
			} else {
				$cityId = (int)$cityData['city_id'];
				$log ("The city is already exists, ID $cityId\n");
			}

			$log ("Setting city with ID $cityId for artist $artistId...\n");
			db_setArtistCity($artistId, $cityId);
		}

		$log ("Processing done.\n\n");
	}

	return $logString;
}