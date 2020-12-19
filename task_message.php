<?php
session_start();
require_once (dirname(__file__). '/function.php');
loginedSession();
notSetChristmasMessage();

// テーブルに表示
$dbh = dbConnect();
$sql = 'SELECT con1_users.name, con1_users.class, con1_task_messages.user_id, con1_task_messages.title, con1_task_messages.message, con1_task_messages.id FROM con1_users INNER JOIN con1_task_messages ON con1_users.id = con1_task_messages.user_id';
$task_messages = $dbh->query($sql);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>課題応援掲示板</title>
</head>
<body>
<?php echo getFlash('error'); ?>
<?php echo getFlash('flash'); ?>
<h1>課題応援掲示板</h1>

プログラミングを頑張る人に応援メッセージをプレゼントしよう！<br>
同期のあの人へ。あの課題に取り組んでいる人へ。<br>
あなたのメッセージが、あの人の頑張る力になる。<br>

<a href="create_task_message_form.php">
新規作成
</a><br>
<table border="1">
<tr>
<th>class</th><th>Name</th><th>Title</th><th>edit</th>
</tr>
<?php foreach ($task_messages as $task_message) : ?>
<tr>
<td><?php echo $task_message['class']; ?>期</td>
<td><?php echo h($task_message['name']); ?></td>
<td>
<a href="task_message_detail.php?task_message_id=<?php echo $task_message['id']; ?>">
<?php echo h($task_message['title']); ?></a>
</td>
<td>
<?php if ((int)$_SESSION['id'] === (int)$task_message['user_id']) : ?>
<a href="edit_task_message.php?task_message_id=<?php echo $task_message['id']; ?>">編集</a>／
<a href="delete_task_message.php?task_message_id=<?php echo $task_message['id']; ?>">削除</a>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</table>
<a href="mypage.php">
マイページへ
</body>
</a><br>
</html>
