#!/usr/bin/env php
<?php
/*

	THIS SCRIPT IS FOR COMMAND-LINE USE ONLY
	   DO NOT INVOKE IT WITH A WEB SERVER

	This script should be used by Liquidsoap instances
	when new metadata is ready. It will save the metadata
	in the track history and also update TuneIn AIR API
	
*/

require_once (dirname(__FILE__).'/../enconfig.php');
require_once (WORK_PATH.'/database.php');
require_once (WORK_PATH.'/functions.php');

function air_update ($type, $data = Array())
{
	$curl = curl_init();

	if($curl) {
		curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
		curl_setopt($curl, CURLOPT_URL, 'http://air.radiotime.com/Playing.ashx'.($type == 'get' ? '?'.http_build_query($data) : ''));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 5);
		curl_setopt($curl, CURLOPT_USERAGENT, 'WaveRadio Platform; +https://github.com/waveradio');
		
		if ($type == 'post')
		{
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		
		$out = curl_exec($curl);
		curl_close($curl);
		return (empty ($out)) ? false : $out;
	} else
		return false;
}

$options = getopt("", Array("title:", "artist:", "station:"));

$stationTag = $options['station'];
if (preg_match('/^[a-z]{1,20}$/', $stationTag) !== 1) {
	die ("Bad station tag specified\n");
}

if (!db_stationExists($stationTag)) {
	die ("No such station: $stationTag\n");
}

if (empty($options['artist'])) {
	$trackMeta = splitMetadata($options['title']);

	if (empty($trackMeta)) {
		die ("Bad --title parameter supplied!\n");
	} else {
		$artistName = $trackMeta['artist'];
		$trackTitle = $trackMeta['title'];
	}	
} else {
	$artistName = trim($options['artist']); $trackTitle = trim($options['title']);
}

$artistProfile = db_searchArtist($artistName);

$artistId = 0;
if (empty($artistProfile)) {
	$artistId = db_addArtist($artistName);

	if ($artistId === 0) {
		die ("ERROR: Could not add new artist to the database, exiting!\n");
	}
} else {
	$artistId = (int)$artistProfile['artist_id'];
}

// We assume that Liquidsoap script will place the tags using this path
$trackPath = file_get_contents(WM_DATA_DIR.'/runtime/tags/'.$stationTag.'.track');

if (empty($trackPath))
	$trackPath = null;

db_addHistory($stationTag, $artistId, trim($options['title']), $trackPath);

if (empty($tunein_credentials[$stationTag])) { // see enconfig.php
	echo ("The station $stationTag is not configured, will not send the track to TuneIn\n");
} else {
	echo ("Sending metadata to TuneIn: $artistName - $trackTitle\n");
	$req_res = air_update('get', Array (
				'partnerId' => $tunein_credentials[$stationTag]['partner_id'],
				'partnerKey' => $tunein_credentials[$stationTag]['partner_key'],
				'id' => $tunein_credentials[$stationTag]['station_id'],
				'artist' => $artistName,
				'title' => $trackTitle
		)
	);
	echo ($req_res);
}

echo ("Metadata processing done!");