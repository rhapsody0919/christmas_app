<?php
session_start();
require_once (dirname(__FILE__). '/function.php');
//認証
loginedSession();
//クリスマスメッセージを設定しているか確認
//notSetChristmasMessage();

if (isset($_GET['task_message_id'])) {
	$task_message_id = (int)$_GET['task_message_id'];
} elseif (isset($_POST['task_message_id'])) {
	$task_message_id = (int)$_POST['task_message_id'];
} else {
	header('Location: task_message.php');
	exit;
}
$user_id = (int)$_SESSION['id'];
$dbh = dbConnect();
$sql = 'SELECT * FROM con1_task_messages WHERE id = :task_message_id AND user_id = :user_id';
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':task_message_id', $task_message_id, PDO::PARAM_INT);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$task_message = $stmt->fetch();
if ($task_message === false) {
	header('Location: task_message.php');
	exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// バリデーションチェック
	$error_messages = [];
	if (empty($_POST['title'])) {
		$error_messages['title'] = '※タイトルを入力してください';
	} elseif (mb_strlen($_POST['title']) > 25 || mb_strlen($_POST['title']) < 3) {
		$error_messages['title'] = '※タイトルは3文字以上25文字以下で入力してください';
	}
	if (empty($_POST['message'])) {
		$error_messages['message'] = '※メッセージを入力してください';
	} elseif (mb_strlen($_POST['message']) > 255 || mb_strlen($_POST['message']) < 8) {
		$error_messages['message'] = '※メッセージは8文字以上255文字以下で入力してください';
	}
	if (empty($error_messages)) {
		$update_title = $_POST['title'];
		$update_message = $_POST['message'];
		$dbh = dbConnect();
		$sql = 'UPDATE con1_task_messages SET message = :update_message, title = :update_title WHERE id = :task_message_id';
		$stmt = $dbh->prepare($sql);
		$stmt->bindValue(':update_title', $update_title);
		$stmt->bindValue(':update_message', $update_message);
		$stmt->bindValue(':task_message_id', $task_message_id, PDO::PARAM_INT);
		$result = $stmt->execute();
		if ($result === false) {
			error_log('Error : update error ' . (__FILE__));
			setFlash('error', 'システムエラー');
			header('Location: task_message.php');
			exit;
		}
		setFlash('flash', '編集しました');
		header('Location: task_message.php');
		exit;
	}
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>課題応援メッセージ編集</title>
</head>
<body>
<h1>課題応援メッセージ編集</h1>
<form action="edit_task_message.php" method="post">
<input type="hidden" name="task_message_id" value="<?php echo $task_message_id; ?>">
<label>タイトル<br>
<input type="text" name="title" value="<?php echo $task_message['title']; ?>" $required>
</label><br>
<p><?php if (!empty($error_messages['title'])) echo $error_messages['title']; ?></p>
<label>メッセージ<br>
<textarea name="message"><?php echo $task_message['message']; ?></textarea>
</label><br>
<p><?php if (!empty($error_messages['message'])) echo $error_messages['message']; ?></p>
<input type="submit" value="編集する">
</form><br>
<a href="task_message.php">課題応援掲示板</a><br>
</body>
</html>
