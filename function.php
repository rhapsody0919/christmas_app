<?php
require (dirname(__FILE__) . '/vendor/autoload.php');

//.envの保存場所指定（カレントに設定）
//$dotenv = Dotenv\Dotenv :: createImmutable(__DIR__);
$dotenv = Dotenv\Dotenv :: createUnsafeImmutable(__DIR__);
$dotenv->load();

// errorログをとる
ini_set('log_errors','on');
ini_set('error_log', dirname(__FILE__) . '/php.log');

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
function getFlash($type) {
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
//25日以降はクリスマスメッセージ画面にリダイレクト
function afterChristmas() {
	$today = date('Y/m/d H:i:s');
	// 切り替える日付を設定
	$target_day = '2020/12/25 00:00:00';
	// 設定した日付以降だったら、切り替える
	if (strtotime($today) > strtotime($target_day)) {
		header('Location: christmas_mypage.php');
		exit;
	}
}
//25日以前はマイページにリダイレクト
function beforeChristmas() {
	$today = date('Y/m/d H:i:s');
	// 切り替える日付を設定
	$target_day = '2020/12/25 00:00:00';
	// 設定した日付以前だったら、切り替える
	if (strtotime($today) < strtotime($target_day)) {
		header('Location: mypage.php');
		exit;
	}
}
//23日以前でクリスマスメッセージを編集、新規作成可能か
function editableChristmasMessage() {
	$today = date('Y/m/d H:i:s');
	// 切り替える日付を設定
	$target_day = '2020/12/23 23:00:00';
	// 設定した日付以降だったら、切り替える
	if (strtotime($today) > strtotime($target_day)) {
		header('Location: christmas_mypage.php');
		exit;
	}
}
//クリスマスメッセージを設定していなかったら、新規作成にリダイレクト
function notSetChristmasMessage() {
	@session_start();
	$user_id = (int)$_SESSION['id'];
	try {
		$dbh = dbConnect();
		$christmas_message_sql = 'SELECT * FROM con1_christmas_messages WHERE user_id = :user_id';
		$christmas_message_stmt = $dbh->prepare($christmas_message_sql);
		$christmas_message_stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$christmas_message_stmt->execute();
		$matching_on_user = $christmas_message_stmt->fetch();
	} catch (PDOException $e) {
		error_log('Error : ' . $e->getMessage());
		header('Location: mypage.php');
		exit;
	}
	if ($matching_on_user === false) {
		//クリスマス以前のみ切り返る
		$today = date('Y/m/d H:i:s');
		$target_day = '2020/12/23 23:00:00';
		if (strtotime($today) < strtotime($target_day)) {
			header('Location: create_mypage_form.php');
			exit;
		}
	}
}
//クリスマスメッセージを設定していたら、マイページにリダイレクト
function SetChristmasMessage() {
	@session_start();
	$user_id = (int)$_SESSION['id'];
	try {
		$dbh = dbConnect();
		$christmas_message_sql = 'SELECT * FROM con1_christmas_messages WHERE user_id = :user_id';
		$christmas_message_stmt = $dbh->prepare($christmas_message_sql);
		$christmas_message_stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$christmas_message_stmt->execute();
		$matching_on_user = $christmas_message_stmt->fetch();
		if ($matching_on_user !== false) {
			header('Location: mypage.php');
			exit;
		}
	} catch (PDOException $e) {
		error_log('Error : ' . $e->getMessage());
		header('Location: mypage.php');
		exit;
	}
}
