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
require_once (WORK_PATH.'/lib/import.inc.php');

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


importTracks($sourceStruct, true);