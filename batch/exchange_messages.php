<?php

require_once (__DIR__ . '/../' . 'function.php');
// データベース接続
$dbh = dbConnect();

// マッチングテーブルの取得
$sql = 'SELECT user_id_1, user_id_2 FROM con1_matching_users';
$stmt = $dbh->query($sql);
if ($stmt === false) {
	error_log('Error : select error matchingテーブル' . (__FILE__));
	exit;
}
$matching_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// クリスマステーブルからマッチングオフのユーザーidを取得
$sql = 'SELECT con1_christmas_messages.user_id FROM con1_christmas_messages INNER JOIN con1_users ON con1_christmas_messages.user_id = con1_users.id WHERE con1_users.matching  = 0';
$stmt = $dbh->query($sql);
if ($stmt === false) {
	error_log('Error : select error ' . (__FILE__));
	exit;
}
$matching_off_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// マッチングオフのクリスマスメッセージ交換相手を決定
$matching = [];
$count = (int)count($matching_off_users);
if ($count % 2 === 1) {
	//奇数だった場合の交換相手を挿入
	$remaining = 38;
	$message = 'クリスマスメッセージ';
	$sql = 'INSERT INTO con1_christmas_messages (user_id, message) VALUES (:user_id, :message)';
	$stmt = $dbh->prepare($sql);
	$stmt->bindValue(':user_id', $remaining, PDO::PARAM_INT);
	$stmt->bindValue(':message', $message, PDO::PARAM_STR);
	$result = $stmt->execute();
	if ($result === false) {
		error_log('Error : insert error ' . (__FILE__));
		setFlash('error', 'システムエラー');
		header('Location: mypage.php');
		exit;
	}
	//交換相手を一人選択
	$arr_key_remain = array_rand($matching_off_users, 1);
	$matching[] = ['user_id' => (int)$matching_off_users[$arr_key_remain]['user_id'], 'present_by' => $remaining];
	unset($matching_off_users[$remaining]);
	$count = $count - 1;
}
$count = $count / 2;
for ($i = 0; $i < $count; $i++) {
	$arr_key = array_rand($matching_off_users, 2);
	$matching[] = ['user_id' => (int)$matching_off_users[$arr_key[0]]['user_id'], 'present_by' => (int)$matching_off_users[$arr_key[1]]['user_id']];
	unset($matching_off_users[$arr_key[0]]);
	unset($matching_off_users[$arr_key[1]]);
}

// マッチングidを配列にまとめる
foreach ($matching_users as $user) {
	$matching[] = ['user_id' => (int)$user['user_id_1'], 'present_by' => (int)$user['user_id_2']];
}

// インサート用の配列
$sql = 'INSERT INTO con1_christmas_exchanges(user_id, present_by) VALUES';

$array_sql1 = [];
//行の繰り返し
foreach($matching as $key1 => $value1){
	$array_sql2 = [];
	//列（カラム）の繰り返し
	foreach($value1 as $key2 => $value2){
		$array_sql2[] = ':' . $key2 . $key1;
	}
	$array_sql1[] = '(' . implode(',', $array_sql2) . ')';
}
$sql .= implode(',', $array_sql1);
//bind処理
$stmt = $dbh->prepare($sql);
foreach($matching as $key1 => $value1){
	foreach($value1 as $key2 => $value2){
		$stmt->bindValue(':' . $key2 . $key1, $value2, PDO::PARAM_INT);
	}
}
$result = $stmt->execute();
if ($result === false) {
	error_log('Error : insert error ' . (__FILE__));
	exit;
}


