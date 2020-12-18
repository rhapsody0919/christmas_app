<?php
session_start();
require_once (dirname(__FILE__). '/function.php');
loginedSession();
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
<p>タイトルとメッセージを入力してください</p>
<label>タイトル<br>
<input type="text" name="title">
</label><br>
<label>メッセージ<br>
<textarea name="message"></textarea>
</label><br>
<input type="submit" value="投稿">
</form><br>
<a href="task_message.php">掲示板に戻る</a>
</body>
</html>
