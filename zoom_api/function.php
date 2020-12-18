<?php
require (dirname(__FILE__) . '/../vendor/autoload.php');

use GuzzleHttp\Client;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Builder;

//.envの保存場所指定（一つ上の階層に設定）
//$dotenv = Dotenv\Dotenv :: createImmutable(realpath('../')); 間違い
$dotenv = Dotenv\Dotenv :: createUnsafeImmutable(__DIR__ . '/..');
$dotenv->load();

const BASE_URI = 'https://api.zoom.us/v2/';


//jwtTokenを作成する
function createJwtToken()
{
	$api_key = getenv('ZOOM_API_KEY');
	$api_secret = getenv('ZOOM_API_SECRET');

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
	try {
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
	//ステータスコードを取得
	} catch (Exception $e) {
		$error_code = json_decode($e->getResponse()->getBody()->getContents())->code;
	}
}


