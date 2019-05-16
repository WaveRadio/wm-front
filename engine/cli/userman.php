#!/usr/bin/env php
<?php
/*

	THIS SCRIPT IS FOR COMMAND-LINE USE ONLY
	   DO NOT INVOKE IT WITH A WEB SERVER

	This script should be used in an emergency case
	of administrative user management.
	
*/

require_once (dirname(__FILE__).'/../enconfig.php');
require_once (WORK_PATH.'/database.php');
require_once (WORK_PATH.'/functions.php');

define ('DEFAULT_PASSWORD', 'berkana');

$options = getopt("", Array("action:", "username:", "password:"));

switch ($options['action']) {
	case 'reset': 
		if (empty($options['username'])) {
			die ("No username set, exiting\n");
		}

		if (db_adminUserExists($options['username'])) {
			db_setAdminUserHash($options['username'], enhashPassword($options['username'], DEFAULT_PASSWORD, 'admin'));
			die ("Successfully reset password for the specified user\n");
		} else {
			die ("No such username\n");
		}
		break;

	case 'add':
		if (empty($options['username'])) {
			die ("No username set, exiting\n");
		}

		if (db_adminUserExists($options['username'])) {
			die ("This user does already exist\n");
		} else {
			db_addAdminUser($options['username'], enhashPassword($options['username'], DEFAULT_PASSWORD, 'admin'));
			die ("Successfully added a new user with default password.\n".
				 "[!] Don't forget to change it right now!\n");
		}
		break;

	default: 
		die ("Bad action.\n".
			 "Usage: userman.php --action <reset|add> --username <username>\n");
		break;
}