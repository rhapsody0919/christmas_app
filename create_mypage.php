<?php
session_start();
require_once (dirname(__FILE__). '/function.php');
loginedSession();
editableChristmasMessage();
setChristmasMessage();
$user_id = $_SESSION['id'];
if (!empty($_POST['message'])) {
	if (!empty($_POST['matching'])) {
		if ((int)$_POST['matching'] === 0 || (int)$_POST['matching'] === 1) {
			if (mb_strlen($_POST['message']) <= 255 && mb_strlen($_POST['message']) >= 30) {
				$user_message = $_POST['message'];
				$user_matching = $_POST['matching'];
				$dbh = dbConnect();
				//メッセージ登録
				$sql = "INSERT INTO con1_christmas_messages (user_id, message) VALUES (:user_id, :message)";
				$stmt = $dbh->prepare($sql);
				$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
				$stmt->bindValue(':message', $user_message, PDO::PARAM_STR);
				$result = $stmt->execute();
				if ($result === false) {
					error_log('Error : insert error ' . (__FILE__));
					setFlash('error', 'システムエラー');
					header('Location: mypage.php');
					exit;
				}

				//マッチング登録
				$sql2 = "UPDATE con1_users SET matching = :matching WHERE id = :id";
				$stmt2 = $dbh->prepare($sql2);
				$stmt2->bindValue(':id', $user_id, PDO::PARAM_INT);
				$stmt2->bindValue(':matching', $user_matching, PDO::PARAM_INT);
				$result = $stmt2->execute();
				if ($result === false) {
					error_log('Error : update error ' . (__FILE__));
					setFlash('error', 'システムエラー');
					header('Location: mypage.php');
					exit;
				}
				setFlash('flash', ' ボトルメッセージを作成しました');
				header('Location: mypage.php');
				exit;
			} else {
				$error_message['message'] = 'ボトルメッセージを30文字以上255字以内で作成してください';
			}
		} else {
			$error_message['message'] = 'マッチング機能を選択してください';
		}
	} else {
		$error_message['message'] = 'マッチング機能を選択してください';
	}
} else {
	$error_message['message'] ='ボトルメッセージを入力してください';
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
<meta charset="UTF-8">
<title>クリスマスメッセージ新規作成結果ページ</title>
</head>

<body>
<h1>ボトルメッセージ新規作成結果</h1>
<p>
<?php
if (!empty($error_message['message'])) {
	echo $error_message['message'];
}
?>
</p>
<p><button onclick="location.href='create_mypage_form.php'">作成に戻る</button></p>
</body>

</html>
