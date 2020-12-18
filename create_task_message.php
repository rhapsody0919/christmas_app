<?php
session_start();
require_once (dirname(__FILE__). '/function.php');
loginedSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// バリデーションチェック
	$error_messages = [];
	//タイトルの入力
	if (empty($_POST['title'])) {
		$error_messages['title'] = '※タイトルを記入してください';
	} elseif (mb_strlen($_POST['title']) > 25 || mb_strlen($_POST['title']) < 3) {
		$error_messages['title'] = '※タイトルは3文字以上25文字以下で入力してください';
	}
	//メッセージの入力
	if (empty($_POST['message'])) {
		$error_messages['message'] = '※メッセージを記入してください';
	} elseif (mb_strlen($_POST['message']) > 255 || mb_strlen($_POST['message']) < 3) {
		$error_messages['message'] = '※メッセージは3文字以上255文字以下で入力してください';
	}

	if (empty($error_messages)) {
//新規作成されたメッセージをinsert、表示
		$title = $_POST['title'];
		$message = $_POST['message'];
		$user_id = $_SESSION['id'];
		$dbh = dbConnect();
		$sql = 'INSERT INTO con1_task_messages (user_id, message, title) VALUES (:user_id, :message, :title)';
		$stmt = $dbh->prepare($sql);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->bindValue(':message', $message, PDO::PARAM_STR);
		$stmt->bindValue(':title', $title, PDO::PARAM_STR);
		$stmt->execute();
		setFlash('flash', '新規メッセージ作成しました');
		header('Location: task_message.php');
		exit;
	}

} else {
	header('Location: task_message.php');
	exit;
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>課題応援メッセージ新規作成</title>
</head>
<body>
<h1>課題応援メッセージ新規作成</h1>
<p><strong>入力内容を確認してください</strong></p>
<p><?php if (!empty($error_messages['title'])) echo $error_messages['title']; ?></p>
<p><?php if (!empty($error_messages['message'])) echo $error_messages['message']; ?></p>
<a href="create_task_message_form.php">戻る</a>
<br>
<a href="task_message.php">掲示板に戻る</a>
</body>
</html>
