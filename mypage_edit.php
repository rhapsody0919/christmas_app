<?php
session_start();
require 'function.php';
$user_id = $_SESSION['id'];
if (!empty($_POST['message'])) {
	if (mb_strlen($_POST['message']) <= 400) {
		if ($_POST['matching'] == 0 || $_POST['matching'] == 1) {
			$user_matching = $_POST['matching'];
			$user_message = $_POST['message'];
			$dbh = dbConnect();

		//名前とマッチ機能のアップデート
			$sql2 = "UPDATE con1_users SET matching =:matching WHERE id = :id";
			$stmt2  = $dbh->prepare($sql2);
			$stmt2->bindValue(':id', $user_id, PDO::PARAM_INT);
			$stmt2->bindValue(':matching', $user_matching, PDO::PARAM_INT);
			$stmt2->execute();

		//クリスマスメッセージのアップデート
			$sql3 ="UPDATE con1_christmas_messages SET message =:message WHERE user_id =:user_id";
			$stmt3 = $dbh->prepare($sql3);
			$stmt3->bindValue(':user_id', $user_id, PDO::PARAM_INT);
			$stmt3->bindValue(':message', $user_message, PDO::PARAM_STR);
			$stmt3->execute();

		//データベースから最新のマッチ情報を取得
			$sql4 = "SELECT * FROM con1_users WHERE id =:id";
			$stmt4 = $dbh->prepare($sql4);
			$stmt4->bindValue(':id', $user_id, PDO::PARAM_INT);
			$stmt4->execute();
			$new_info = $stmt4->fetch();

		//最新のクリスマスメッセージの取得
			$sql5 = "SELECT * FROM con1_christmas_messages WHERE user_id =:user_id";
			$stmt5 = $dbh->prepare($sql5);
			$stmt5->bindValue(':user_id', $user_id, PDO::PARAM_INT);
			$stmt5->execute();
			$new_message = $stmt5->fetch();

			$db = null;
			header('Location: https://procir-study.site/Kan414/christmas/christmas_app/mypage.php');
			exit;
		} else {
			$error_message['matching'] = "マッチング機能を選択してください";
		}
	} else {
		$error_message['message'] = "クリスマスメッセージの文字数を400字以内で作成してください";
	}
} else {
	$error_message['message'] = "クリスマスメッセージを作成してください";
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
<meta charset="UTF-8">
<title>編集エラーページ</title>
</head>

<body>
<?php
if (!empty($error_message['matching'])) {
	echo $error_message['matching'];
} elseif (!empty($error_message['message'])) {
	echo $error_message['message'];
}
?>
</body>

</html>
