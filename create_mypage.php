<?php
session_start();
require 'function.php';
$user_id = $_SESSION['id'];
if (!empty($_POST['message'])) {
	$user_message = $_POST['message'];
	$dbh = dbConnect();
	$sql = "INSERT INTO con1_christmas_messages (user_id, message) WHERE (:user_id, :message)";
	$stmt = $dbh->prepare($sql);
	$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
	$stmt->bindValue(':message', $user_message, PDO::PARAM_STR);
	$stmt->execute();
	$dbh = null;
	header('Location: https://procir-study.site/Kan414/christmas/christmas_app/mypage.php');
	exit;
} else {
	$error_message['message'] ='クリスマスメッセージを作成してください';
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
<meta charset="UTF-8">
<title>クリスマスメッセージ新規作成結果ページ</title>
</head>

<body>
<?php
if (!empty($error_message['message'])) {
	echo $error_message['message'];
}
?>
</body>

</html>
