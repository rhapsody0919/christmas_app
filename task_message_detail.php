<?php
session_start();
require_once (dirname(__FILE__). '/function.php');
loginedSession();
//notSetChristmasMessage();

if (!isset($_GET['task_message_id'])) {
	header('Location: task_message.php');
	exit;
}
$task_message_id = (int)$_GET['task_message_id'];
$dbh = dbConnect();
$sql = 'SELECT con1_users.name, con1_users.class, con1_task_messages.title, con1_task_messages.message FROM con1_users INNER JOIN con1_task_messages ON con1_users.id = con1_task_messages.user_id WHERE con1_task_messages.id = :task_message_id';
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':task_message_id', $task_message_id, PDO::PARAM_INT);
$stmt->execute();
$task_message = $stmt->fetch(PDO::FETCH_ASSOC);
if ($task_message === false) {
	header('Location: task_message.php');
	exit;
}
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
<h2><?php echo $task_message['class']; ?>期の<?php echo $task_message['name']; ?>さんより</h2>
<h3>タイトル：<?php echo $task_message['title']; ?></h3>
<h3>本文：<?php echo $task_message['message']; ?></h3>
<br>
<a href="task_message.php">掲示板へ戻る</a>
</body>
<br>
</html>
