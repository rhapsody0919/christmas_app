<?php
session_start();
require 'function.php';
$user_id = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="ja">

<head>
<meta charset="UTF-8">
<title>クリスマスメッセージ新規作成ページ</title>
</head>

<body>
<h1>クリスマスメッセージ新規作成ページ</h1>
<form action="create_mypage.php" method="post">
<p>クリスマスメッセージ:</p>
<textarea name="message" cols="60" rows="8"></textarea>
<p>*編集は可能です</p>
<p>
<input type="submit" value="作成する">
</p>
</form>
</body>

</html>
