<?php
require_once (dirname(__FILE__). '/function.php');
session_start();
$users_count = count(getAllUsers());
$messages_count = count(getAllTaskMessages()) + count(getAllBottleMessages());

?>
<!doctype html>
<html class="no-js h-100" lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>「プロサーがサンタクロース」 -ボトルメッセージから始まる新しいつながり-</title>
<meta name="description" content="A high-quality &amp; free Bootstrap admin dashboard template pack that comes with lots of templates and components.">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" id="main-stylesheet" data-version="1.1.0" href="styles/shards-dashboards.1.1.0.min.css">
<link rel="stylesheet" href="styles/extras.1.1.0.min.css">
<script async defer src="https://buttons.github.io/buttons.js"></script>
</head>
<body class="h-100">
<div class="container-fluid">
  <div class="row">
	<!-- Main Sidebar -->
	<aside class="main-sidebar col-12 col-md-3 col-lg-2 px-0">
	  <div class="main-navbar">
		<nav class="navbar align-items-stretch navbar-light bg-white flex-md-nowrap border-bottom p-0">
		  <a class="navbar-brand w-100 mr-0" href="index.php" style="line-height: 25px;">
			<div class="d-table m-auto">
			  <img id="main-logo" class="d-inline-block align-top mr-1" style="max-width: 25px;" src="images/630.gif" alt="プロサーがサンタクロース">
			  <span class="d-none d-md-inline ml-1">プロサーがサンタクロース</span>
			</div>
		  </a>
		  <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
			<i class="material-icons">&#xE5C4;</i>
		  </a>
		</nav>
	  </div>
	  <div class="nav-wrapper">
		<ul class="nav flex-column">
<?php if(!$_SESSION): ?>
		  <li class="nav-item">
		  <a class="nav-link " href="create_user.php">
			<span>ユーザー登録</span>
		  </a>
		  </li>
		  <li class="nav-item">
		  <a class="nav-link " href="login.php">
			<span>ログイン</span>
		  </a>
		  </li>
<?php endif; ?>
<?php if($_SESSION): ?>
		  <li class="nav-item">
		  <a class="nav-link" href="mypage.php">
			<i class="material-icons">edit</i>
			<span>マイページ</span>
		  </a>
		  </li>
		  <li class="nav-item">
		  <a class="nav-link" href="task_message.php">
			<i class="material-icons">vertical_split</i>
			<span>課題応援メッセージ掲示板</span>
		  </a>
		  </li>
		  <li class="nav-item">
		  <a class="nav-link " href="logout.php">
			<span>ログアウト </span>
		  </a>
		  </li>
<?php endif; ?>
		</ul>
	  </div>
	</aside>
	<!-- End Main Sidebar -->
	<main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
		<div class="main-navbar sticky-top bg-success">
			<!-- Main Navbar -->
			<nav class="navbar align-items-stretch navbar-light flex-md-nowrap p-0">
			  <ul class="navbar-nav border-left flex-row ">
				<li class="nav-item dropdown">
				  <div class="dropdown-menu dropdown-menu-small">
<?php if(!$_SESSION): ?>
					<a class="dropdown-item" href="create_user.php">
					  ユーザー登録</a>
					<a class="dropdown-item" href="login.php">
					  ログイン</a>
<?php endif; ?>
<?php if($_SESSION): ?>
					<a class="dropdown-item" href="mypage.php">
					  <i class="material-icons">edit</i>マイページ</a>
					<a class="dropdown-item" href="task_message.php">
					  <i class="material-icons">vertical_split</i>課題応援メッセージ掲示板</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item text-danger" href="#">
					  <i class="material-icons text-danger">&#xE879;</i>ログアウト</a>
				  </div>
<?php endif; ?>
				</li>
			  </ul>
			  <nav class="nav bg-white">
				<a href="#" class="nav-link nav-link-icon toggle-sidebar d-md-inline d-lg-none text-center border-left" data-toggle="collapse" data-target=".header-navbar" aria-expanded="false" aria-controls="header-navbar">
				  <i class="material-icons">&#xE5D2;</i>
				</a>
			  </nav>
			</nav>
			<!-- End Navbat -->
	<div class="main-content-container container-fluid px-4">

	  <!-- Page Header -->
	  <div class="page-header row no-gutters py-4">
		<div class="col-12 col-sm-4 text-center text-sm-left mb-0">
		  <span class="text-uppercase page-subtitle text-white">〜ボトルメッセージから始まる新しいつながり〜</span>
		  <h3 class="page-title text-white">プロサーがサンタクロース
		  <img id="main-logo" class="d-inline-block align-top mr-1" style="max-width: 25px;" src="images/630.gif" alt="プロサーがサンタクロース">
			</h3>
		</div>
	  </div>
	  <!-- End Page Header -->
	  <!-- Small Stats Blocks -->
	  <div class="row">
		<div class="col-lg col-md-6 col-sm-6 mb-4">
		  <div class="stats-small stats-small--1 card card-small">
			<div class="card-body p-0 d-flex">
			  <div class="d-flex flex-column m-auto">
				<div class="stats-small__data text-center">
				  <span class="stats-small__label text-uppercase">messages</span>
				  <h6 class="stats-small__value count my-3"><?php echo $messages_count; ?></h6>
				</div>
			  </div>
			  <canvas height="120" class="blog-overview-stats-small-1"></canvas>
			</div>
		  </div>
		</div>
		<div class="col-lg col-md-4 col-sm-6 mb-4">
		  <div class="stats-small stats-small--1 card card-small">
			<div class="card-body p-0 d-flex">
			  <div class="d-flex flex-column m-auto">
				<div class="stats-small__data text-center">
				  <span class="stats-small__label text-uppercase">Users</span>
				  <h6 class="stats-small__value count my-3"><?php echo $users_count; ?></h6>
				</div>
			  </div>
			  <canvas height="120" class="blog-overview-stats-small-4"></canvas>
			</div>
		  </div>
		</div>
		<!-- End Small Stats Blocks -->
	  </div>
	  <!-- End Page Header -->
			<div class="row">
			  <div class="col-lg-8">
				<div class="card card-small mb-4">
				  <ul class="list-group list-group-flush">
					<li class="list-group-item p-3">
					  <div class="row">
						<div class="col">
<h6>使い方</h6>
<ol>
<li class="font-weight-bold">クリスマスの日にボトルメッセージを届けよう!</li>
一人一通の特別なボトルメッセージを送ろう!
誰に届くかはクリスマスまでのお楽しみ!<br>
どんな内容を書くかは自由!<br>
あなたの夢、プロサーを始めたきっかけ、誰にも言えない秘密。<br>
あなただけの思いをボトルに込めよう！！<br>
<br>
<li class="font-weight-bold">マッチング機能</li>
マッチングとは？<br>
マッチングすることで、お互いに特別なクリスマス版ボトルメッセージを送り合うことが出来るよ！
<br>マッチングした相手とは、Slack上でDMを交換したり、zoomをしたりしよう!
忘れられないクリスマスになること間違いなし！今すぐあなたもマッチングON！
<br>
<br>
<li class="font-weight-bold">課題応援メッセージ掲示板</li>
プロサーのみんなに応援メッセージをプレゼントしよう！<br>
同期のあの人へ。憧れのあの人に。頑張って欲しいあの人に。あの課題に取り組んでいる人へ。<br>
あなたのメッセージが、みんなの頑張る力になる。
</ol>

						</div>
					  </div>
					</li>
				  </ul>
				</div>
			  </div>
			</div>
<?php if (!isset($_SESSION['id'])) : ?>
<p class="pb-4"><a class="btn btn-danger" href="create_user.php">新規ユーザー登録</a></p>
<?php endif; ?>
	  </main>
	</div>
  </div>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
  <script src="https://unpkg.com/shards-ui@latest/dist/js/shards.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Sharrre/2.0.1/jquery.sharrre.min.js"></script>
  <script src="scripts/extras.1.1.0.min.js"></script>
  <script src="scripts/shards-dashboards.1.1.0.min.js"></script>
  <script src="scripts/app/app-blog-overview.1.1.0.js"></script>
  </body>
  </html>
