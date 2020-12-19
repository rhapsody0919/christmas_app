<?php
//require_once (dirname(__FILE__). '/get_users_name.php');
require_once (dirname(__FILE__). '/../function.php');
require_once (dirname(__FILE__). '/conversations_open.php');

//.envの保存場所指定（一つ上の階層に設定）
$dotenv = Dotenv\Dotenv :: createUnsafeImmutable(__DIR__ . '/..');
$dotenv->load();
$token = getenv('SLACK_BOT_USER_TOKEN');
//マッチングしたペアを取得
$matching_results = getMatchingResults();

$base_url = "https://slack.com/api/chat.postMessage";

foreach ($matching_results as $matching_result) {
	$users_arr = [];
	$users_arr[] = getUserById($matching_result['user_id_1']);
	$users_arr[] = getUserById($matching_result['user_id_2']);
	//$user2 = getUserById($matching_results['user_id_2']);
	$i = 0;
	foreach ($users_arr as $user) {
		$dm_channel_id = conversationsOpen($user['slack_id']);
		//DMのチャンネルIDが正しくない場合
		if (!$dm_channel_id) {
			$i++;
			continue;
		}
		print_r($dm_channel_id);
		if ($i == 0) {
			$partner_slack_id = $users_arr[1]['slack_id'];
			$data = [
				'token' => $token,
				'channel' => $dm_channel_id,
				'text' => 'マッチング相手の' . "<@$partner_slack_id>" . 'さんが待ってるよ!! 早速DMしてみよう!',
			];
		} else {
			$partner_slack_id = $users_arr[0]['slack_id'];
			$data = [
				'token' => $token,
				'channel' => $dm_channel_id,
				'text' => 'マッチング相手の' . "<@$partner_slack_id>" . 'さんが待ってるよ!! 早速DMしてみよう!',
			];
		}

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
		curl_exec($curl);
		curl_close($curl);
		$i++;
	}
}

