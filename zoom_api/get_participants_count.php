<?php
require_once (dirname(__FILE__) . '/../vendor/autoload.php');
require_once (dirname(__FILE__). '/function.php');

function getParticipantsCount() {
	$uuid = 'cKeQOMApR4u/XiKywjkZLA==';
	$method = 'GET';
	$path = 'past_meetings/' . $uuid;
	$client_params = [
		'base_uri' => BASE_URI,
	];
	$result = sendRequest($method, $path, $client_params);
	return $result;
}

$result = getParticipantsCount();
print_r($result);
print_r($result['participants_count']);
