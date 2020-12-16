<?php

function slackNotification($message) {
	// Webhook URL
	$url = getenv('WEBHOOK_URL');

	//DBからマッチングしたユーザーとzoom URLを取得する

	// メッセージ
	$param = array(
		'username' => 'christmas_app',
		//'text' => 'AさんとBさんがマッチングされました。12/25日(金)22:00になりましたら、以下のURLからお入りください。メリークリスマス!!',
		'text' => $message,
	);

	// メッセージをjson化
	$param_json = json_encode($param);

	// payloadの値としてURLエンコード
	$param_post = 'payload=' . urlencode($param_json);
	echo 'a';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $param_post);
	curl_exec($ch);
	curl_close($ch);
}

//slackNotification();

