<?php
session_start();
require_once (dirname(__FILE__) . '/function.php');
//ログイン時の情報を一時保存し、取得
$user_id = $_SESSION['id'];
$dbh = dbConnect();

//名前、マッチング機能の取得
$sql1 = "SELECT * FROM con1_users WHERE id =:id";
$stmt1 = $dbh->prepare($sql1);
$stmt1->bindValue(':id', $user_id, PDO::PARAM_INT);
$stmt1->execute();
$user_info = $stmt1->fetch();

//クリスマスメッセージの取得
$sql2 = "SELECT * FROM con1_christmas_messages WHERE user_id =:user_id";
$stmt2 = $dbh->prepare($sql2);
$stmt2->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt2->execute();
$christmas_message = $stmt2->fetch();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
<meta charset="UTF-8">
<title>クリスマスメッセージ編集ページ</title>
</head>

<body>
<h1>ボトルクリスマスメッセージ編集ページ</h1>
<form action="mypage_edit.php" method="post">
<p>名前:
<?php echo $user_info['name']; ?>
</p>
<p>マッチング機能:
<?php if ((int)$user_info['matching'] === 1) : ?>
<input type="radio" name="matching" value="1" checked="checked">ON
<input type="radio" name="matching" value="0">OFF
<?php else : ?>
<input type="radio" name="matching" value="1">ON
<input type="radio" name="matching" value="0" checked="checked">OFF
<?php endif; ?>
</p>
<p>ボトルメッセージ<br>
<textarea name="message" cols="60"  rows="8"><?php echo $christmas_message['message']; ?></textarea>
</p>
<p>
<button onclick="location.href='mypage_edit.php'">変更する</button><br>
</p>
</form>
<p>
<button onclick="location.href='mypage.php'">マイページに戻る</button>
</p>
</body>

</html>