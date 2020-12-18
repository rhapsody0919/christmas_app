<?php
require_once (dirname(__FILE__) . '/../vendor/autoload.php');
require_once (dirname(__FILE__). '/function.php');

function getParticipantsCount($uuid) {
	//$uuid = 'cKeQOMApR4u/XiKywjkZLA==';
	$method = 'GET';
	$path = 'past_meetings/' . $uuid;
	$client_params = [
		'base_uri' => BASE_URI,
	];
	if (sendRequest($method, $path, $client_params) == 3001) {
		return false;
	}
	$result = sendRequest($method, $path, $client_params);
	return $result['participants_count'];
}

//過去のやつ↓
//$result = getParticipantsCount('cKeQOMApR4u/XiKywjkZLA==');
//有効期限が切れていないやつ
//$result = getParticipantsCount('8lad1pyWTpSlpiVDImubOw==');
