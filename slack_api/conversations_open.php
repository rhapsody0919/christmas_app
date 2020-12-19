<?php
require_once (dirname(__FILE__). '/get_users_name.php');
function conversationsOpen($user_slack_id) {
	//.envの保存場所指定（一つ上の階層に設定）
	$dotenv = Dotenv\Dotenv :: createUnsafeImmutable(__DIR__ . '/..');
	$dotenv->load();
	//$token = getenv('SLACK_TOKEN');
	$token = getenv('SLACK_BOT_USER_TOKEN');
	$url = 'https://slack.com/api/conversations.open';

	$data = [
		'token' => $token,
		'users' => $user_slack_id,
	];

	$headers = [
		'Authorization: Bearer ' . $token,
		'Content-Type: application/json; charset=UTF-8',
	];

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
	//リクエストにヘッダーを含める
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, true);
	$response = curl_exec($curl);
	$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
	$body = substr($response, $header_size);
	$result = json_decode($body, true);
	curl_close($curl);
	//エラーが起こった場合
	if (!$result['ok'] == 1) {
		echo $user_slack_id;
		echo 'エラー';
		return false;
	}
	return $result['channel']['id'];
}

//conversationsOpen('UQFTZKX0E');

