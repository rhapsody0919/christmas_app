<?php
require_once (dirname(__FILE__). '/../slack_api/slack_notification.php');

//スラック通知
$message = '「プロサーがサンタクロース」 -ボトルメッセージから始まる新しいつながり-にご参加ありがとうございます!' . "\n" .
	'ボトルメッセージに自分の想いや夢は書きましたでしょうか？' . "\n" .
	'ボトルメッセージを登録したら他のプロサー生が書いた素敵なメッセージを受け取ることができます[メッセージ入力締め切り~12/23 18:00]' . "\n" .
	'https://procir-study.site/ishibashi331/christmas_app/login.php';
slackNotification($message);
