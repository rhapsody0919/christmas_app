<?php
require './vendor/autoload.php';

//.envの保存場所指定（カレントに設定）
$dotenv = Dotenv\Dotenv :: createImmutable(__DIR__);
$dotenv->load();


// エンティティ化
function h($s) {
	return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// データベース
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
