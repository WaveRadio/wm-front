<?php

array_shift($route); // remove /public/
switch ($route[0])
{
	case 'history' :
		require_once ('engine/public_api/history.php'); 
		break;

	case 'current' :
		require_once ('engine/public_api/current.php'); 
		break;

	default :
		json_respond (-1, 'Bad Public API request');
		break;
}