<?php
require_once (dirname(__FILE__). '/../slack_api/slack_notification.php');

//スラック通知
$message = 'メリークリスマス!!' . "\n" .
	'プロサー生からクリスマスプレゼントが届いています!' . "\n" .
	'下記のURLからプレゼントを確認してみましょう!' . "\n" .
	'URL : ~~';
slackNotification($message);
