<?php
require (dirname(__FILE__) . '/vendor/autoload.php');

//.envの保存場所指定（カレントに設定）
$dotenv = Dotenv\Dotenv :: createImmutable(__DIR__);
$dotenv->load();


// エンティティ化
function h($s) {
	return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// データベース接続
function dbConnect() {
	$dsn = $_ENV['DB_CONNECTION'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'] . ';charset=utf8';
	$username = $_ENV['DB_USERNAME'];
	$password = $_ENV['DB_PASSWORD'];
	$pdo = new PDO($dsn, $username, $password);
	try {
		$pdo = new PDO($dsn, $username, $password);
	} catch (PDOException $e) {
		echo 'DBerror'  . $e->getMessage();
		exit;
	}
	return $pdo;
}

// 認証
function unloginedsession() {
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
