<?php

// Notices are unneeded in this case
// error_reporting(E_ALL & ~E_NOTICE);

require_once ('engine/enconfig.php');
require_once ('engine/database.php');
require_once ('engine/interface.php');
require_once ('engine/functions.php');

$route = explode('/', $_GET['route']);

if ($route[0] === 'public') {
	require_once 'engine/public_api.php';
	die();
}

// No need to start session on /public/ requests
session_start();

if (empty($_SESSION['user_id']) && $route[0] !== 'auth') {
	header ('HTTP/1.1 302 Redirect');
	header ('Location: /auth');
}

switch ($route[0]) {
	case 'api':
		require_once 'engine/api.php';
		break;

	case 'auth':
		require_once 'engine/auth.php';
		break;

	case '':
		require_once 'engine/main.php';
		break;

	default:
		display404();
		break;
}