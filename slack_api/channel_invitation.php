<?php
require_once (dirname(__FILE__) . '/../vendor/autoload.php');
require_once (dirname(__FILE__). '/../function.php');

function channelInvitation() {
	//.envの保存場所指定（一つ上の階層に設定）
	$dotenv = Dotenv\Dotenv :: createUnsafeImmutable(__DIR__ . '/..');
	$dotenv->load();

	$token = getenv('SLACK_TOKEN');
	$base_url = 'https://slack.com/api/conversations.invite';

	//DBから全てのuser_idを取得
	$users = getAllUsers();

	foreach ($users as $user) {
		$data = [
			'token' => $token,
			'channel' => getenv('SLACK_CHANNEL_TEST_ID'),
			//'users' => 'U017WLZC5S9,UQ8Q7FSQH',
			'users' => $user['slack_id'],
		];

		$headers = [
			'Authorization: Bearer ' . $token,
			'Content-Type: application/json; charset=UTF-8',
		];

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $base_url);
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
		if (!$result['ok']) {
			error_log('slackチャンネル:' . getenv('SLACK_CHANNEL_TEST_ID') . 'にuser_idが' . $user['id'] . 'のユーザを招待失敗しました。' . "\n", 3, __DIR__ . '/../log/slack_channel_invitation.log');

			continue;
		}

		curl_close($curl);
	}
}



