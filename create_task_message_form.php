<?php
session_start();
require_once (dirname(__FILE__). '/function.php');
loginedSession();
notSetChristmasMessage();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>課題応援メッセージ新規作成</title>
</head>
<body>
<h1>課題応援メッセージ新規作成</h1>
<form action="create_task_message.php" method="post">
<p>課題応援メッセージを贈ります<br>
タイトルとメッセージを入力してください</p>
<label>タイトル *3文字以上25文字以下<br>
<input type="text" name="title" required>
</label><br>
<label>メッセージ *8文字以上225文字以下<br>
<textarea name="message" cols="60" rows="8" required></textarea>
</label><br>
<input type="submit" value="投稿">
</form><br>
<a href="task_message.php">掲示板に戻る</a>
</body>
</html>
