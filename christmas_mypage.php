<?php
session_start();
require_once (dirname(__FILE__). '/function.php');
loginedSession();

//クリスマス以前か判定
beforeChristmas();

$id = $_SESSION['id'];
$name = $_SESSION['name'];
$dbh = dbConnect();

// クリスマスメッセージをもらった相手のidを取得
$sql = 'SELECT * FROM con1_christmas_exchanges WHERE user_id = :user_id OR present_by = :present_by';
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
$stmt->bindValue(':present_by', $id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch();
if ($result !== false) {
	if ($id === $result['user_id']) {
		$present_id = $result['present_by'];
		$user_id = $result['user_id'];
	} else {
		$present_id = $result['user_id'];
		$user_id = $result['present_by'];
	}
	// クリスマスメッセージ、相手のユーザー情報を取得
	$sql = 'SELECT con1_users.name, con1_users.class, con1_christmas_messages.message FROM con1_users INNER JOIN con1_christmas_messages ON con1_users.id = con1_christmas_messages.user_id WHERE con1_christmas_messages.user_id = :user_id';
	$stmt = $dbh->prepare($sql);
	$stmt->bindValue(':user_id', $present_id, PDO::PARAM_INT);
	$stmt->execute();
	$present = $stmt->fetch();
	if ($present === false) {
		$messages['error'] = 'クリスマスメッセージは設定されていませんでした。引き続き他のサービスをご利用ください。';
		error_log('Error : select error ' . (__FILE__));
	}
} else {
	$messages['error'] = 'クリスマスメッセージは設定されていませんでした。引き続き他のサービスをご利用ください。';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>ボトルメッセージ</title>
</head>
<body>
<?php echo getFlash('error'); ?>
<?php echo getFlash('flash'); ?>
<h1>ボトルメッセージ</h1>
<?php if (!empty($messages['error'])) : ?>
<p><?php echo $messages['error']; ?></p>
<?php else : ?>
<p>
ボトルメッセージが届きました
<p>
<p>
<?php echo h($present['name']); ?>
<?php if ($present['class'] === 0) : ?>
(運営)
<?php else : ?>
(<?php echo $present['class']; ?>期生)
<?php endif; ?>
さんより
</p>
<p><?php echo $present['message']; ?></p>
<?php endif; ?>
<a href="task_message.php">課題応援掲示板</a><br>
<a href="logout.php">ログアウト</a><br>
</body>
</html>
