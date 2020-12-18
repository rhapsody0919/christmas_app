<?php

require_once (__DIR__ . '/../' . 'function.php');
// データベース接続
$dbh = dbConnect();

// マッチングテーブルの取得
$sql = 'SELECT user_id_1, user_id_2 FROM con1_matching_users';
$stmt = $dbh->query($sql);
if ($stmt === false) {
	exit;
}
$matching_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// クリスマステーブルからマッチングオフのユーザーidを取得
//$sql = 'SELECT con1_christmas_messages.user_id, con1_users.name FROM con1_christmas_messages INNER JOIN con1_users ON con1_christmas_messages.user_id = con1_users.id WHERE con1_users.matching  = 0';
$sql = 'SELECT con1_test_messages.user_id FROM con1_test_messages INNER JOIN con1_users ON con1_test_messages.user_id = con1_users.id WHERE con1_users.matching = 0';
$stmt = $dbh->query($sql);
if ($stmt === false) {
	exit;
}
$matching_off_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// マッチングオフのクリスマスメッセージ交換相手を取得
$matching = [];
$count = (int)count($matching_off_users);
if ($count % 2 === 1) {
	$arr_key_remain = array_rand($matching_off_users, 1);
	$matching[] = ['user_id' => (int)$matching_off_users[$arr_key_remain]['user_id'], 'present_by' => (int)2244];
	unset($matching_off_users[$arr_key_remain]);
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
$sql = 'INSERT INTO con1_tests(user_id, present_by) VALUES';

$arySql1 = [];
//行の繰り返し
foreach($matching as $key1 => $val1){
	$arySql2 = [];
	//列（カラム）の繰り返し
	foreach($val1 as $key2 => $val2){
		$arySql2[] = ':' . $key2 . $key1;
	}
	$arySql1[] = '(' . implode(',', $arySql2) . ')';
}
$sql .= implode(',', $arySql1);
//bind処理
$stmt = $dbh->prepare($sql);
foreach($matching as $key1 => $val1){
	foreach($val1 as $key2 => $val2){
		$stmt->bindValue(':' . $key2 . $key1, $val2, PDO::PARAM_INT);
	}
}
$stmt->execute();
