<?php
session_start();
require_once (dirname(__FILE__). '/function.php');
unloginedSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// バリデーションチェック
	$error_messages = [];
	if (empty($_POST['mail'])) {
		$error_messages['mail'] = '※メールアドレスを入力してください';
	} elseif (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
		$error_messages['mail'] = '※Email形式を入力してください';
	}
	if (empty($_POST['password'])) {
		$error_messages['password'] = '※パスワードを入力してください';
	} elseif (!preg_match(("/^[0-9a-zA-Z]+$/"), $_POST['password'])) {
		$error_messages['password'] = '※半角英数字で入力してください';
	}

	if (empty($error_messages)) {
		$mail = $_POST['mail'];
		$password = $_POST['password'];
		$dbh = dbConnect();
		$sql = 'SELECT * FROM con1_users WHERE mail = :mail';
		$stmt = $dbh->prepare($sql);
		$stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
		$stmt->execute();
		$user = $stmt->fetch();
		if ($user !== false) {
			if (password_verify($password, $user['pass'])) {
				$_SESSION['id'] = $user['id'];
				$_SESSION['name'] = $user['name'];
				$_SESSION['class'] = $user['class'];
				setFlash('flash_message', 'ログインしました');
				header('Location: index.php');
				exit;
			} else {
				$error_messages['password'] = '※パスワードが間違っています';
			}
		} else {
			$error_messages['mail'] = '※メールアドレスを正しく入力してください';
		}
	}
}
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
<p><?php if (!empty($error_messages['mail'])) echo $error_messages['mail']; ?></p>
<label>パスワード<br>
<input type="password" name="password">
</label><br>
<p><?php if (!empty($error_messages['password'])) echo $error_messages['password']; ?></p>
<input type="submit" value="ログイン">
</form><br>
<a href="create_user.php">新規登録</a><br>
</body>
</html>
