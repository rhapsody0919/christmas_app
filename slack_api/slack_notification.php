<?php
require_once (dirname(__FILE__) . '/../vendor/autoload.php');

function slackNotification($message) {
	//.envの保存場所指定（一つ上の階層に設定）
	$dotenv = Dotenv\Dotenv :: createUnsafeImmutable(__DIR__ . '/..');
	$dotenv->load();
	// Webhook URL
	$url = getenv('WEBHOOK_URL');

	//DBからマッチングしたユーザーとzoom URLを取得する

	// メッセージ
	$param = array(
		'username' => 'プロサーがサンタクロース',
		'text' => $message,
	);

	// メッセージをjson化
	$param_json = json_encode($param);

	// payloadの値としてURLエンコード
	$param_post = 'payload=' . urlencode($param_json);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $param_post);
	curl_exec($ch);
	curl_close($ch);
}

//slackNotification();

