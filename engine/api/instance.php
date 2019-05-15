<?php

function processAdd()
{
	$instance_tag = (int)$_POST['instance-tag'];
	
	json_respond(0, 'OK');
}

array_shift($route); // remove /instance/
switch ($route[0])
{
	case 'add' :
		processAdd();
		break;

	default:
		json_respond (-1, 'Bad API request');
}