<?php
require_once (dirname(__FILE__). '/../slack_api/slack_notification.php');
require_once (dirname(__FILE__). '/../slack_api/channel_invitation.php');

//チャンネル招待
channelInvitation();

//スラック通知
$message = 'プロサークリスマスボトルメッセージにご参加ありがとうございます。' . "\n" .
	'瓶にメッセージを入れて海原に送り出すように自分の想いや夢をメンバーに届けましょう!' . "\n" .
	'このチャンネルでメッセージやマッチングの通知を行います!';
slackNotification($message);
