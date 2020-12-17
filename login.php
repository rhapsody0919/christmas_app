<?php
session_start();
require_once (dirname(__FILE__). '/function.php');
unloginedSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// バリデーションチェック
	$error_messages = [];
	if (empty($_POST['name'])) {
		$error_messages['name'] = '※ニックネームを入力してください';
	}
	if (empty($_POST['password'])) {
		$error_messages['password'] = '※パスワードを入力してください';
	} elseif (!preg_match(("/^[0-9a-zA-Z]+$/"), $_POST['password'])) {
		$error_messages['password'] = '※半角英数字で入力してください';
	}

	if (empty($error_messages)) {
		$name = $_POST['name'];
		$password = $_POST['password'];
		$dbh = dbConnect();
		$sql = 'SELECT * FROM con1_users WHERE name = :name';
		$stmt = $dbh->prepare($sql);
		$stmt->bindValue(':name', $name, PDO::PARAM_STR);
		$stmt->execute();
		$user = $stmt->fetch();
		if ($user !== false) {
			if (password_verify($password, $user['pass'])) {
				$_SESSION['id'] = $user['id'];
				$_SESSION['name'] = $user['name'];
				$_SESSION['class'] = $user['class'];
				$_SESSION['slack_id'] = $user['slack_id'];
				setFlash('flash', 'ログインしました');
				header('Location: index.php');
				exit;
			} else {
				$error_messages['name'] = '※ニックネームまたはパスワードが違います';
			}
		} else {
			$error_messages['name'] = '※ニックネームまたはパスワードが違います';
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
<p><?php echo getFlash('error'); ?></p>
<h1>ログイン</h1>
<form action="login.php" method="post">
<p>ニックネームとパスワードを入力してください</p>
<label>ニックネーム<br>
<input type="text" name="name" required>
</label><br>
<p><?php if (!empty($error_messages['name'])) echo $error_messages['name']; ?></p>
<label>パスワード<br>
<input type="password" name="password" required>
</label><br>
<p><?php if (!empty($error_messages['password'])) echo $error_messages['password']; ?></p>
<input type="submit" value="ログイン">
</form><br>
<a href="create_user.php">新規登録</a><br>
</body>
</html>
