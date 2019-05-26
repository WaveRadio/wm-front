<?php

function processGetCode() {
	$instanceTag = $_GET['instance_tag'];
	if (!validateInstanceTag($instanceTag)) {
		json_respond(1, 'Bad instance tag');
	}

	$codePath = '';

	switch ($_GET['type']) {
		case 'liquidsoap':
			$codePath = WM_DATA_DIR . '/data/scripts/' . $instanceTag . '.liq';
			break;

		case 'icecast':
			$codePath = WM_DATA_DIR . '/data/icecast/' . $instanceTag . '.xml';
			break;

		default:
			json_respond(1, 'Bad instance type tag');
	}

	$code = file_get_contents($codePath);

	if (empty($code)) {
		json_respond(2, 'Empty or non-existent code file');
	} else {
		json_respond(0, $code);
	}
}

array_shift($route); // remove /instance/
switch ($route[0])
{
	case 'code' :
		processGetCode();
		break;

	default:
		json_respond (-1, 'Bad API request');
}