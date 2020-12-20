<?php
session_start();
require_once (dirname(__file__). '/function.php');
loginedSession();
notSetChristmasMessage();

// テーブルに表示
$dbh = dbConnect();
$sql = 'SELECT con1_users.name, con1_users.class, con1_task_messages.user_id, con1_task_messages.title, con1_task_messages.message, con1_task_messages.id FROM con1_users INNER JOIN con1_task_messages ON con1_users.id = con1_task_messages.user_id';
$task_messages = $dbh->query($sql);

//フラッシュメッセージの取得
$flash_error_msg = getFlash('error');
$flash_success_msg = getFlash('flash');

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
          <a class="nav-link " href="task_message.php">
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
			<h3>
		</div>
	  </div>
	  <!-- End Page Header -->
			<div class="row">
			  <div class="col-lg-8">
				<div class="card card-small mb-4">
				  <ul class="list-group list-group-flush">
					<li class="list-group-item p-3">
					  <div class="row">
						<div class="col">
	プログラミングを頑張る人に応援メッセージをプレゼントしよう！<br>
	同期のあの人へ。あの課題に取り組んでいる人へ。<br>
	あなたのメッセージが、あの人の頑張る力になる。<br>
						</div>
					  </div>
					</li>
				  </ul>
				</div>
			  </div>
			</div>


<div>
	<a class="btn btn-white" href="create_task_message_form.php">
新規作成はこちら
</a>
</div>
<br>
<!--フラッシュメッセージ(成功)-->
<?php if (!empty($flash_success_msg)): ?>
<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">×</span>
	  </button>
	  <i class="fa fa-check mx-2"></i>
<?php echo $flash_success_msg; ?>
</div>
<?php endif; ?>

<!--フラッシュメッセージ(失敗)-->
<?php if (!empty($flash_error_msg)): ?>
<div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">×</span>
	  </button>
	  <i class="fa fa-check mx-2"></i>
<?php echo $flash_error_msg; ?>
</div>
<?php endif; ?>

            <!-- Default Light Table -->
            <div class="row">
              <div class="col">
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                    <h6 class="m-0">課題応援メッセージ掲示板</h6>
                  </div>
                  <div class="card-body p-0 pb-3 text-center">
                    <table class="table mb-0">
                      <thead class="bg-light">
                        <tr>
                          <th scope="col" class="border-0">class</th>
                          <th scope="col" class="border-0">Name</th>
                          <th scope="col" class="border-0">Title</th>
                          <th scope="col" class="border-0">edit</th>
                        </tr>
                      </thead>
                      <tbody>
<?php foreach ($task_messages as $task_message) : ?>
<tr>
<td>
<?php if((int)$task_message['class'] === 0) : ?>
運営
<?php else : ?>
<?php echo $task_message['class']; ?>期
<?php endif; ?>
</td>
<td><?php echo h($task_message['name']); ?></td>
<td>
<a href="task_message_detail.php?task_message_id=<?php echo $task_message['id']; ?>">
<?php echo h($task_message['title']); ?></a>
</td>
<td>
<?php if ((int)$_SESSION['id'] === (int)$task_message['user_id']) : ?>
<a href="edit_task_message.php?task_message_id=<?php echo $task_message['id']; ?>">編集</a>／
<a href="delete_task_message.php?task_message_id=<?php echo $task_message['id']; ?>">削除</a>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
			<!-- End Default Light Table -->
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
