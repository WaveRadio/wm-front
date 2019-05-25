<?php

array_shift($route); // remove /api/
switch ($route[0])
{
	case 'instance' :
		require_once ('engine/api/instance.php'); 
		break;

	case 'library' :
		require_once ('engine/api/library.php'); 
		break;

	case 'user': 
		require_once ('engine/api/user.php'); 
		break;

	default :
		json_respond (-1, 'Bad API request');
		break;
}