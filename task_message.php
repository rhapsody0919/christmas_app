<?php
session_start();
require_once (dirname(__FILE__). '/function.php');
loginedSession();

// テーブルに表示
$dbh = dbConnect();
$sql = 'SELECT con1_users.name, con1_users.class, con1_task_messages.title, con1_task_messages.message, con1_task_messages.id FROM con1_users INNER JOIN con1_task_messages ON con1_users.id = con1_task_messages.user_id';
$data = $dbh->query($sql);

//編集処理（絶賛途中）


//削除処理（絶賛途中）
$members = $dbh->prepare('SELECT * FROM con1_users WHERE id=?');
$members->execute(array($_SESSION['id']));
$members = $members->fetch();


?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>課題応援掲示板</title>
</head>
<body>
<h1>課題応援掲示板</h1>
<a href="create_task_message_form.php">
新規作成
</a><br>
<table border="1">
<tr>
<th>class</th><th>Name</th><th>Title</th><th>edit</th>
</tr>
<?php foreach ($data as $datum) : ?>
<tr>
<td><?php echo $datum['class']; ?></td>
<td><?php echo $datum['name']; ?></td>
<td><a href="task_message_detail.php?class=<?php echo $datum['class']; ?>&name=<?php echo $datum['name']; ?>&title=<?php echo $datum['title']; ?>&message=<?php echo $datum['message']; ?>">
<?php echo $datum['title']; ?></a></td>
<td><a href="edit_task_message.php?task_message_id=<?php echo $datum['id']; ?>">編集</a>／<a href="delete_task_message.php?task_message_id=<?php echo $datum['id']; ?>">削除</a></td>
</tr>
<?php endforeach; ?>
</table>
<a href="mypage.php">
マイページへ
</body>
</a><br>
</html>
