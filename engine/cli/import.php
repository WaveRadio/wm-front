#!/usr/bin/env php
<?php
/*

	THIS SCRIPT IS FOR COMMAND-LINE USE ONLY
	   DO NOT INVOKE IT WITH A WEB SERVER

	This script should be used by to update
	the artist database from a JSON file.
	See https://github.com/waveradio/wm-front for 
	more detailed documentation on JSON structure 
	accepted by this script.
	
*/

require_once (dirname(__FILE__).'/../enconfig.php');
require_once (WORK_PATH.'/database.php');
require_once (WORK_PATH.'/functions.php');

$options = getopt("", Array("source:"));
$sourceFile = $options['source'];

if (empty($sourceFile)) {
	die ("No source JSON file specified!\n".
		 "Usage: import.php --source <path_to_source.json>\n");
}

if (!file_exists($sourceFile)) {
	die ("No such file: $sourceFile\n");
}

$sourceStruct = json_decode(file_get_contents($sourceFile), true);
if (empty($sourceStruct)) {
	die ("Malformed or empty JSON file\n");
}

// TODO: move this part to a shared script file
// ------------------------8<-----------------------

foreach ($sourceStruct as $artist) {

	echo ('Processing artist '. $artist['artist']."\n");

	$artistProfile = db_searchArtist($artist['artist']);

	$artistId = 0;
	if (empty($artistProfile)) {

		echo ("No artist with this name/alias found, creating new...\n");
		$artistId = db_addArtist($artist['artist']);

		if ($artistId === 0) {
			die ("ERROR: Could not add new artist to the database, exiting!\n");
		}
	} else {
		$artistId = (int)$artistProfile['artist_id'];
		echo ("This artist is already exists in our database, ID $artistId\n");
	}

	echo ("Processing links: getting links for current artist...\n");

	$artistLinks = db_getArtistLinks($artistId);

	if (empty($artistLinks)) {
		echo ("Artist has no links at all, OK, just adding everything we have\n");
	} else {
		echo ("WARNING: Removing all artist links to replace them with a new list!\n");
		db_removeAllArtistLinks($artistId);
	}

	if (!empty($artist['bandcamp'])) {
		db_addArtistLink($artistId, $artist['bandcamp']);
	}

	if (!empty($artist['vk'])) {
		db_addArtistLink($artistId, $artist['vk']);
	}

	echo ("Links are processed, processing city...\n");

	if (empty($artist['city'])) {
		echo ("No city specified, passing this away\n");
	} else {
		echo ('The specified city is '.$artist['city'].", checking if this city exists in our DB...\n");
		$cityId = 0;

		$cityData = db_searchArtistCity($artist['city']);

		if (empty($cityData)) {
			echo ("This city isn't known yet, adding\n");

			$cityId = (int)db_addArtistCity($artist['city']);

			if ($cityId === 0) {
				die ("ERROR: Could not add new city to the database, exiting!\n");
			}
		} else {
			$cityId = (int)$cityData['city_id'];
			echo ("The city is already exists, ID $cityId\n");
		}

		echo ("Setting city with ID $cityId for artist $artistId...\n");
		db_setArtistCity($artistId, $cityId);
	}

	echo ("Processing done.\n\n");
}

// ------------------------>8-----------------------
