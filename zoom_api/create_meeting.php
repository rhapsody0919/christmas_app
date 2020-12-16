<?php
require_once (dirname(__FILE__) . '/../vendor/autoload.php');
require_once (dirname(__FILE__). '/function.php');

function createMeeting() {
	$hour = gmdate("H");
	$minute = gmdate("i");
	$second = gmdate("s");
	$month = gmdate("n");
	$day = gmdate("j");
	$year = gmdate("Y");
	$timestamp = gmmktime($hour, $minute, $second, $month, $day, $year);
	$now = gmdate('Y-m-d\TH:i:s', $timestamp + 60 * 60 * 9) . 'Z';
	$user_id = getUserId();
	$params = [
		'topic' => 'テスト',
		//1->instant meeting, 2->スケジュールミーティング
		'type' => 2,
		'time_zone' => 'Asia/Tokyo',
		//'start_time' => '2020-12-14T18:00:00Z',
		'start_time' => $now,
		'agenda' => 'ズームAPIを試す',
		'settings' => [
			//ビデオなどに関しては、userのzoom設定次第かも。
			//ホストのビデオをONにするか
			'host_video' => false,
			//参加者のビデオをONにするか
			'participant_video' => false,
			//参加者の入室を許可する方法を設定 0->automatically, 1->manually, 2->no registration required
			'approval_type' => 0,
			//ホスト参加前に参加を許可する
			'join_before_host' => true,
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


