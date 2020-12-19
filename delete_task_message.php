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
	if (empty($error_messages)) {
		$update_title = $_POST['title'];
		$update_message = $_POST['message'];
		$dbh = dbConnect();
		$sql = 'DELETE FROM con1_task_messages WHERE id = :task_message_id';
		$stmt = $dbh->prepare($sql);
		$stmt->bindValue(':task_message_id', $task_message_id, PDO::PARAM_INT);
		$result = $stmt->execute();
		if ($result === false) {
			error_log('Error : delete error ' . (__FILE__));
			setFlash('error', 'システムエラー');
			header('Location: task_message.php');
			exit;
		}
		setFlash('flash', '削除しました');
		header('Location: task_message.php');
		exit;
	}
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>課題応援メッセージ削除</title>
</head>
<body>
<h1>課題応援メッセージ削除</h1>
<p>タイトル：<?php echo $task_message['title']; ?></p>
<p>メッセージ：<?php echo $task_message['message']; ?></p>
<p>本当に削除しますか？</p>
<form action="delete_task_message.php" method="post">
<input type="hidden" name="task_message_id" value="<?php echo $task_message_id; ?>">
<input type="submit" value="削除する">
</form><br>
<a href="task_message.php">課題応援掲示板</a><br>
</body>
</html>


