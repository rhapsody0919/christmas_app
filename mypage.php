<?php
session_start();
require_once (dirname(__FILE__). '/function.php');
//ログイン時にdbから取得したデータを一時的に保存する
$user_id = $_SESSION['id'];
//var_dump($user_id);
$dbh = dbConnect();

//名前、何期生、マッチングのデータ取得
$sql1 = "SELECT * FROM con1_users WHERE id = :id";
$stmt1 = $dbh->prepare($sql1);
$stmt1->bindValue(':id', $user_id, PDO::PARAM_INT);
$stmt1->execute();
$user_info = $stmt1->fetch();

//クリスマスメッセージの取得
$sql2 = "SELECT * FROM con1_christmas_messages WHERE user_id = :user_id";
$stmt2 = $dbh->prepare($sql2);
$stmt2->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt2->execute();
$christmas_message = $stmt2->fetch();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
<meta charset="UTF-8">
<title>マイページ</title>
</head>

<body>
<h1>マイページ</h1>
<p>名前:
<?php
echo  $user_info['name'];
?>
</p>
<p>何期生:
<?php
echo $user_info['class'] . '期生';
?>
</p>
<p>マッチング:
<?php
if ((int)$user_info['matching'] === 0){
	echo "OFF";
}
if ((int)$user_info['matching'] === 1){
	echo "ON";
}
?>
</p>
<p>クリスマスメッセージ:
<?php
echo $christmas_message['message'];
?>
</p>
<p><button onclick="location.href='mypage_edit_form.php'">編集する</button></p>
<p><button onclick="location.href='task_message.php'">掲示板へ</button></p>
</body>

</html>
