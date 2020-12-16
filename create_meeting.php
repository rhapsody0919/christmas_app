<?php
require (dirname(__FILE__) . '/vendor/autoload.php');

use GuzzleHttp\Client;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Builder;

//.envの保存場所指定（カレントに設定）
$dotenv = Dotenv\Dotenv :: createImmutable(__DIR__);
$dotenv->load();

const BASE_URI = 'https://api.zoom.us/v2/';

//jwtTokenを作成する
function createJwtToken()
{
	$api_key = $_ENV['ZOOM_API_KEY'];
	$api_secret = $_ENV['ZOOM_API_SECRET'];
	$signer = new Sha256;
	$key = new Key($api_secret);
	$time = time();
	$jwt_token = (new Builder())->setIssuer($api_key)
		->expiresAt($time + 3600)
		->sign($signer, $key)
		->getToken();
	return $jwt_token;
}

function getUserId() {
	$method = 'GET';
	$path = 'users';
	$client_params = [
		'base_uri' => BASE_URI,
	];
	$result = sendRequest($method, $path, $client_params);
	$user_id = $result['users'][0]['id'];
	return $user_id;
}

function sendRequest($method, $path, $client_params) {
	$client = new Client($client_params);
	$jwt_token = createJwtToken();
	$response = $client->request($method,
		$path,
		[
			'headers' => [
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $jwt_token,
			]
		]);
	$result_json = $response->getBody()->getContents();
	$result = json_decode($result_json, true);
	return $result;
}

function createMeeting() {
	$user_id = getUserId();
	$params = [
		'topic' => 'テスト',
		//1->instant meeting, 2->スケジュールミーティング
		'type' => 2,
		'time_zone' => 'Asia/Tokyo',
		'start_time' => '2020-12-14T18:00:00Z',
		//'start_time' => '2020-12-13T15:00:00Z',
		'agenda' => 'ズームAPIを試す',
		'settings' => [
			//ビデオなどに関しては、userのzoom設定次第かも。
			//ホストのビデオをONにするか
			'host_video' => false,
			//参加者のビデオをONにするか
			'participant_video' => false,
			//参加者の入室を許可する方法を設定 0->automatically, 1->manually, 2->no registration required
			'approval_type' => 0,
			//参加者がどういった手段でミーティングの音声に参加するか
			'audio' => 'both',
			'enforce_login' => false,
			'waiting_room' => false,
			'registrants_email_notification' => false
		]
	];
	$method = 'POST';
	$path = 'users/' . $user_id . '/meetings';
	$client_params = [
		'base_uri' => BASE_URI,
		'json' => $params
	];
	$result = sendRequest($method, $path, $client_params);
	return $result;
}

for ($i = 0; $i < 3; $i++) {
	$meeting = createMeeting();
	print_r($meeting);
	echo '<br>';
	print_r($meeting['join_url']);
	echo '<br>';
	echo '<br>';
}

