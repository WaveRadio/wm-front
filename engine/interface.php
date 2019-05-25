<?php
// require_once ('menu.php');

function json_respond ($status, $payload = Array(), $public = false) {

	if ($public && API_ALLOW_CORS) {
		header ('Access-Control-Allow-Origin: *');
	}

	header('Content-Type: application/json');
	die(json_encode(Array('status' => (int)$status, 'payload' => $payload)));
}

function display ($template, $content = Array()) {

	global $route;

	if (!empty($_SESSION['sys_message'])) {
		$content['sys_message']['text'] = $_SESSION['sys_message'];
		$content['sys_message']['type'] = $_SESSION['sys_message_type'];

		unset($_SESSION['sys_message']);
		unset($_SESSION['sys_message_type']);
	}

	$content['current_timestamp'] = time();

	require_once ('engine/template/'.$template.'.tpl');
	die();
}

function display404()
{
	$content = Array();
	$content['title'] = _('404: Not Found');
	$content['description'] = _('The path you are trying to access is invalid.');
	display('404', $content);
}


function displayAuth($csrfToken) {
	$content = Array(
		'text_username' => _('username'),
		'text_password' => _('password'),
		'text_submit'   => _('go'),
		'title'         => _('WaveManager Core Authentication'),

		'csrf_token'    => $csrfToken
	);
	
	require_once('engine/template/auth.tpl');
}

function displayError($message) {
	header('HTTP/1.1 500 Internal Server Error');
	$content = Array();
	$content['title'] = _('System error');
	$content['error_message'] = $message;
	display('error', $content);
}