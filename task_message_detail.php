<?php
session_start();
require_once (dirname(__FILE__). '/function.php');
loginedSession();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>メッセージ詳細</title>
</head>
<body>
<h1>メッセージ詳細</h1>
<br>
<h2><?php echo $_GET['class']; ?>期の<?php echo $_GET['name']; ?>さんより</h2>
<h3>タイトル：<?php echo $_GET['title']; ?></h3>
<h3>本文：<?php echo $_GET['message']; ?></h3>
<br />
<a href="task_message.php">
掲示板へ戻る
</a>
</body>
<br>
</html>
