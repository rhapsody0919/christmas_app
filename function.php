<?php
require (dirname(__FILE__) . '/vendor/autoload.php');

//.envの保存場所指定（カレントに設定）
//$dotenv = Dotenv\Dotenv :: createImmutable(__DIR__);
$dotenv = Dotenv\Dotenv :: createUnsafeImmutable(__DIR__);
$dotenv->load();

// errorログをとる
ini_set('log_errors','on');
ini_set('error_log','php.log');

// デバッグフラグ
$debug_flg = false;
//デバッグ書き込み
function debug($str){
	global $debug_flg;
	if ($debug_flg) {
		error_log('デバッグ：' . $str);
	}
}

// エンティティ化
function h($s) {
	return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// データベース接続
function dbConnect() {
	$dsn = getenv('DB_CONNECTION') . ':host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE') . ';charset=utf8';
	$username = getenv('DB_USERNAME');
	$password = getenv('DB_PASSWORD');

	$pdo = new PDO($dsn, $username, $password);
	try {
		$pdo = new PDO($dsn, $username, $password);
	} catch (PDOException $e) {
		error_log('error:' . $e->getMessage());
		exit;
	}
	return $pdo;
}

// 認証
function unloginedSession() {
	@session_start();
	// ログインしていれば / に遷移
	if (isset($_SESSION['id'])) {
		header('Location: mypage.php');
		exit;
	}
}
function loginedSession() {
	@session_start();
	// ログインしていなければ /login.php に遷移
	if (!isset($_SESSION['id'])) {
		header('Location: login.php');
		exit;
	}
}

// フラッシュメッセージをセット
function setFlash($type, $message) {
	@session_start();
	$_SESSION[$type] = $message;
}
// フラッシュメッセージを取得
function getflash($type) {
	@session_start();
	$message = '';
	if (!empty($_SESSION[$type])) {
		$message = $_SESSION[$type];
		unset($_SESSION[$type]);
	}
	return $message;
}

function getAllUsers() {
	try {
		$dbh = dbConnect();
		$get_all_users_sql = 'SELECT * FROM con1_users';
		$get_all_users_stm = $dbh->prepare($get_all_users_sql);
		//SQL文実行
		$get_all_users_stm->execute();
		$users = $get_all_users_stm->fetchAll(PDO::FETCH_ASSOC);
		return $users;
	} catch (PDOException $e) {
		$msg = 'Error : ' . $e->getMessage();
		return false;
	}
}

//マッチングありに指定しているuserをDBから取得
function getMatchingOnUsers() {
	try {
		$dbh = dbConnect();
		$get_matching_on_users_sql = 'SELECT * FROM con1_users WHERE matching = 1';
		$get_matching_on_users_stm = $dbh->prepare($get_matching_on_users_sql);
		//SQL文実行
		$get_matching_on_users_stm->execute();
		$matching_on_users = $get_matching_on_users_stm->fetchAll(PDO::FETCH_ASSOC);
		return $matching_on_users;
	} catch (PDOException $e) {
		$msg = 'Error : ' . $e->getMessage();
		return false;
	}
}

//マッチングありに指定しているuserをDBから取得
function getUserById($id) {
	try {
		$dbh = dbConnect();
		$get_user_sql = 'SELECT * FROM con1_users WHERE id = ' . $id;
		$get_user_stm = $dbh->prepare($get_user_sql);
		//SQL文実行
		$get_user_stm->execute();
		$user = $get_user_stm->fetch(PDO::FETCH_ASSOC);
		return $user;
	} catch (PDOException $e) {
		$msg = 'Error : ' . $e->getMessage();
		return false;
	}
}

//マッチングOFFにしている運営ユーザのmatchingをONにupdate
function updateMatchingOn($user_name) {
	try {
		$dbh = dbConnect();
		$update_sql = 'UPDATE con1_users SET matching = 1 WHERE name = ?';
		$update_stm = $dbh->prepare($update_sql);
		$update_stm->bindValue(1, $user_name, PDO::PARAM_STR);
		$update_stm->execute();
		return true;
	} catch (PDOException $e) {
		$msg = 'Error : ' . $e->getMessage();
		return false;
	}
}

//マッチング結果とzoom URLを取得
function getMatchingResults() {
	try {
		$dbh = dbConnect();
		$get_matching_results_sql = 'SELECT * FROM con1_matching_users';
		$get_matching_results_stm = $dbh->prepare($get_matching_results_sql);
		//SQL文実行
		$get_matching_results_stm->execute();
		$matching_results = $get_matching_results_stm->fetchAll(PDO::FETCH_ASSOC);
		return $matching_results;
	} catch (PDOException $e) {
		$msg = 'Error : ' . $e->getMessage();
		return false;
	}
}

//matching_usersテーブルから全てのzoom uuidを取得
function getAllZoomUUID() {
	try {
		$dbh = dbConnect();
		$get_all_zoom_uuid_sql = 'SELECT zoom_uuid FROM con1_matching_users';
		$get_all_zoom_uuid_stm = $dbh->prepare($get_all_zoom_uuid_sql);
		//SQL文実行
		$get_all_zoom_uuid_stm->execute();
		$all_zoom_uuid = $get_all_zoom_uuid_stm->fetchAll(PDO::FETCH_ASSOC);
		return $all_zoom_uuid;
	} catch (PDOException $e) {
		$msg = 'Error : ' . $e->getMessage();
		return false;
	}
}

//matching_usersテーブルのboth_joinedカラムのupdate
function updateBothJoinedByUuid($both_joined, $zoom_uuid) {
	try {
		$dbh = dbConnect();
		$update_sql = 'UPDATE con1_matching_users SET both_joined = ? WHERE zoom_uuid = ?';
		$update_stm = $dbh->prepare($update_sql);
		$update_stm->bindValue(1, $both_joined, PDO::PARAM_STR);
		$update_stm->bindValue(2, $zoom_uuid, PDO::PARAM_STR);
		$update_stm->execute();
		return true;
	} catch (PDOException $e) {
		$msg = 'Error : ' . $e->getMessage();
		return false;
	}
}



