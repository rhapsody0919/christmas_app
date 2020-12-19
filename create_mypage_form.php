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
<h1>クリスマスボトルメッセージ新規作成ページ</h1>
<p>クリスマスメッセージ:</p>
<ul>
<li>プロサーを始めたきっかけ</li>
<li>夢</li>
<li>今頑張っていること</li>
<li>五年後の自分へ</li>
<li>感謝していること</li>
<li>どんな１年だった</li>
<li>誰にも言えない秘密</li>
</ul>
<form action="create_mypage.php" method="post">
<textarea name="message" cols="60" rows="8"></textarea>
<p>*編集は可能です</p>
<p>
<input type="submit" value="作成する">
</p>
</form>
</body>

</html>
