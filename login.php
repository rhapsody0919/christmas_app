<?php
require_once (dirname(__FILE__). '/function.php');
unloginedSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

} else {
	header('Location: https://procir-study.site/ishibashi331/home.php');
	exit;
}
