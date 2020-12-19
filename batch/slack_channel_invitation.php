<?php
require_once (dirname(__FILE__). '/../slack_api/slack_notification.php');
require_once (dirname(__FILE__). '/../slack_api/channel_invitation.php');

//チャンネル招待
channelInvitation();

//スラック通知
$message = '「プロサーがサンタクロース」 -ボトルメッセージから始まる新しいつながり-にご参加ありがとうございます!' . "\n" .
	'このチャンネルでメッセージやマッチングの通知を行います!' . "\n" .
	'下記のURLより"瓶にメッセージを入れて海原に送り出すように"自分の想いや夢をメンバーに届けましょう!' . "\n" .
	'URL : ~~';
slackNotification($message);
