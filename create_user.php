<?php
session_start();

require_once (dirname(__FILE__). '/function.php');
unloginedSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// バリデーションチェック
	$error_messages = [];
	if (!isset($_POST['class'])) {
		$error_messages['class'] = 'プロサー期を選択してください';
	} elseif ((int)$_POST['class'] > 14 || (int)$_POST['class'] === 9) {
		$error_messages['class'] = 'プロサー期を選択してください';
	}
	if (empty($_POST['name'])) {
		$error_messages['name'] = '※ニックネームを入力してください';
	} elseif (mb_strlen($_POST['name']) > 25) {
		$error_messages['name'] = '※ニックネームは25文字以下で入力してください';
	}
	if (empty($_POST['mail'])) {
		$error_messages['mail'] = '※メールアドレスを入力してください';
	} elseif (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
		$error_messages['mail'] = '*Email形式を入力してください';
	} elseif (mb_strlen($_POST['mail']) > 255 || mb_strlen($_POST['mail']) < 3) {
		$error_messages['mail'] = '※メールアドレスは3文字以上255文字以下で入力してください';
	}
	if (empty($_POST['password1'])) {
		$error_messages['password1'] = '※パスワードを入力してください';
	} elseif (!preg_match(('/^[0-9a-zA-Z]+$/'), $_POST['password1'])) {
		$error_messages['password1'] = '※半角英数字で入力してください';
	} elseif (mb_strlen($_POST['password1']) > 100 || mb_strlen($_POST['password1']) < 8) {
		$error_messages['password1'] = '※パスワードは8文字以上100文字以下で入力してください';
	} elseif (empty($_POST['password2'])) {
		$error_messages['password2'] = '※確認用パスワードを入力してください';
	} elseif ($_POST['password1'] !== $_POST['password2']) {
		$error_messages['password2'] = '※パスワードが一致しません';
	}
	if (empty($error_messages)) {
		$class = (int)$_POST['class'];
		$name = $_POST['name'];
		$mail = $_POST['mail'];
		$password = password_hash($_POST['password1'], PASSWORD_DEFAULT);
		$dbh = dbConnect();
		$sql = 'SELECT * FROM con1_users WHERE mail = :mail';
		$stmt = $dbh->prepare($sql);
		$stmt->bindValue(':mail', $mail);
		$stmt->execute();
		$result = $stmt->fetch();
		if ($result === false) {
			$sql = 'INSERT INTO con1_users(name, class, mail, pass) VALUES(:name, :class, :mail, :password)';
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(':name', $name, PDO::PARAM_STR);
			$stmt->bindValue(':class', $class, PDO::PARAM_INT);
			$stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
			$stmt->bindValue(':password', $password, PDO::PARAM_STR);
			$stmt->execute();
			$sql = 'SELECT * FROM con1_users WHERE mail = :mail';
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(':mail', $mail);
			$stmt->execute();
			$user = $stmt->fetch();
			$_SESSION['id'] = $user['id'];
			$_SESSION['name'] = $user['name'];
			$_SESSION['class'] = $user['class'];
			setFlash('flash_message', '登録完了。ログインしました');
			header('Location: index.php');
			exit;
		} else {
			$error_messages['mail'] = '※メールアドレスが使用できません';
		}
	}
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>新規登録</title>
</head>
<body>
<h1>ユーザー新規登録</h1>
<form action="create_user.php" method="post">
<label>プロサー何期生<br>
<select name="class">
<?php for ($i=1; $i<=14; $i++) : ?>
<?php if ($i === 9) continue; ?>
<option value="<?php echo $i; ?>"><?php echo $i; ?>期生</option>
<?php endfor; ?>
<option value="0">運営</option>
</select>
</label>
<br>
<p><?php if (!empty($error_messages['class'])) echo $error_messages['class']; ?></p>
<label>ニックネーム<br>
<input type="text" name="name">
</label><br>
<p><?php if (!empty($error_messages['name'])) echo $error_messages['name']; ?></p>
<label>メールアドレス<br>
<input type="email" name="mail">
</label><br>
<p><?php if (!empty($error_messages['mail'])) echo $error_messages['mail']; ?></p>
<label>パスワード<br>
<input type="password" name="password1">
</label><br>
<p><?php if (!empty($error_messages['password1'])) echo $error_messages['password1']; ?></p>
<label>パスワード(確認用)<br>
<input type="password" name="password2">
</label><br>
<p><?php if (!empty($error_messages['password2'])) echo $error_messages['password2']; ?></p>
<input type="submit" value="登録する">
</form><br>
<a href="login.php">ログイン</a><br>
</body>
</html>
