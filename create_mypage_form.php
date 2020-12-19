<?php
session_start();
require_once (dirname(__FILE__). '/function.php');
$user_id = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="ja">

<head>
<meta charset="UTF-8">
<title>クリスマスメッセージ新規作成ページ</title>
</head>

<body>
<?php echo getFlash('error'); ?>
<?php echo getFlash('flash'); ?>
<h1>ボトルメッセージ新規作成ページ</h1>
<form action="create_mypage.php" method="post">
<p>マッチング機能:
<input type="radio" name="matching" value="1">ON
<input type="radio" name="matching" value="0">OFF
</p>
<p>ボトルメッセージ:</p>
<textarea name="message" cols="60" rows="8"></textarea>
<p>*作成後、編集は可能です</p>
<p>
<input type="submit" value="作成する">
</p>
</form>
</body>

</html>
