#!/usr/bin/env php
<?php
/*

	THIS SCRIPT IS FOR COMMAND-LINE USE ONLY
	   DO NOT INVOKE IT WITH A WEB SERVER

	This script should be used by to Liquidsoap
	to authenticate & authorize users that are
	connecting to the server as a source client
	
*/

require_once (dirname(__FILE__).'/../enconfig.php');
require_once (WORK_PATH.'/database.php');
require_once (WORK_PATH.'/functions.php');

define ('ICY_INTERNAL_USERNAME', 'source'); // ICY clients don't support other than 'source' at their most

$options = getopt("", Array("action:", "password:", "station:"));

switch ($options['action']) {
	case 'authenticate':
	case 'authorize': 
		if (empty($options['password']) || empty($options['station'])) {
			die ("No password or station tag set\n");
		}

		if (!$db_stationExists($options['station'])) {
			die ("Bad station specified\n");
		}

		$passwordHash = enhashPassword(ICY_INTERNAL_USERNAME, $options['password'], 'broadcast');
		$user = db_checkBroadcastUser($passwordHash, $options['station']);

		if (empty($user)) {
			die ($options['action'] === 'authenticate' ? 'null' : 'false');
		} else {
			die ($options['action'] === 'authenticate' ? $user['user_name'] : 'true');
		}
		break;

	default: 
		die ("Bad action.\n".
		 	 "Usage: broadcast.php --action <authenticate|authorize> --password <password> --station <station>\n");
	break;
}
