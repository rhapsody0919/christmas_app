<?php
require_once (dirname(__FILE__). '/function.php');
unloginedSession();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>ログインフォーム</title>
</head>
<body>
<h1>ログイン</h1>
<form action="login.php" method="post">
<p>メールアドレスとパスワードを入力してください</p>
<label>メールアドレス<br>
<input type="email" name="mail">
</label><br>
<label>パスワード<br>
<input type="password" name="pass">
</label><br>
<input type="submit" value="ログイン">
</form><br>
<a href="user_create.php">新規登録</a><br>
</body>
</html>
