<?php
session_start();
require_once (dirname(__FILE__). '/function.php');
loginedSession();

//クリスマス以前か判定
beforeChristmas();

$id = $_SESSION['id'];
$name = $_SESSION['name'];
$messages = [];
$dbh = dbConnect();

// クリスマスメッセージをもらった相手のidを取得
$sql = 'SELECT * FROM con1_christmas_exchanges WHERE user_id = :user_id OR present_by = :present_by';
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
$stmt->bindValue(':present_by', $id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch();
if ($result !== false) {
	if ($id === $result['user_id']) {
		$present_id = $result['present_by'];
		$user_id = $result['user_id'];
	} else {
		$present_id = $result['user_id'];
		$user_id = $result['present_by'];
	}
	// クリスマスメッセージ、相手のユーザー情報を取得
	$sql = 'SELECT con1_users.id, con1_users.name, con1_users.class, con1_christmas_messages.message FROM con1_users INNER JOIN con1_christmas_messages ON con1_users.id = con1_christmas_messages.user_id WHERE con1_christmas_messages.user_id = :present_id OR con1_christmas_messages.user_id = :user_id';
	$stmt = $dbh->prepare($sql);
	$stmt->bindValue(':present_id', $present_id, PDO::PARAM_INT);
	$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
	$stmt->execute();
	$present = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if ($present !== false) {
		// メッセージ数が正しいか
		if (count($present) === 2) {
			if ($present[0]['id'] === $user_id) {
				$my_message = $present[0];
				$present_message = $present[1];
			} else {
				$my_message = $present[1];
				$present_message = $present[0];
			}
		} else {
			$messages['error'] = 'クリスマスメッセージは設定されていませんでした。引き続き他のサービスをご利用ください。';
			error_log('Error : メッセージ数エラー error ' . (__FILE__));
		}
	} else {
		$messages['error'] = 'クリスマスメッセージは設定されていませんでした。引き続き他のサービスをご利用ください。';
		error_log('Error : select error ' . (__FILE__));
	}
} else {
	$messages['error'] = 'クリスマスメッセージは設定されていませんでした。引き続き他のサービスをご利用ください。';
}
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
<?php if($_SESSION): ?>
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
		  <img id="main-logo" class="d-inline-block align-top mr-1" style="max-width: 25px;" src="images/630.gif" alt="プロサーがサンタクロース"></h3>
		</div>
	  </div>
	  <!-- End Page Header -->
<!--フラッシュメッセージ(成功)-->
<?php if (!empty($flash_success_msg)): ?>
<div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
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
<br>
<?php endif; ?>
			<div class="row">
			  <div class="col-lg-8">
				<div class="card card-small mb-4">
				  <div class="card-header border-bottom">
					<h6 class="m-0">ボトルメッセージ for クリスマス</h6>
				  </div>
				  <ul class="list-group list-group-flush">
					<li class="list-group-item p-3">
					  <div class="row">
						<div class="col">

<?php if (!empty($messages['error'])) : ?>
<p><?php echo $messages['error']; ?></p>
<?php else : ?>
<p>
ボトルメッセージが届きました
</p>
			<div class="row">
			  <div class="col-lg-8">
				<div class="card card-small mb-4">
				  <ul class="list-group list-group-flush">
					<li class="list-group-item p-3">
					  <div class="row">
						<div class="col">
<h6>
<?php echo h($present_message['name']); ?>
<?php if ($present_message['class'] === 0) : ?>
(運営)
<?php else : ?>
(<?php echo $present_message['class']; ?>期生)
<?php endif; ?>
さんより
</h6>
<p><?php echo h($present_message['message']); ?></p>

						</div>
					  </div>
					</li>
				  </ul>
				</div>
			  </div>
			</div>
			<div class="row">
			  <div class="col-lg-8">
				<div class="card card-small mb-4">
				  <ul class="list-group list-group-flush">
					<li class="list-group-item p-3">
					  <div class="row">
						<div class="col">
<h6>
<?php echo h($my_message['name']); ?>さんが送ったメッセージ
</h6>
<p><?php echo h($my_message['message']); ?></p>

						</div>
					  </div>
					</li>
				  </ul>
				</div>
			  </div>
			</div>
<?php endif; ?>
<a class="btn btn-danger" href="task_message.php">課題応援掲示板</a><br>


						</div>
					  </div>
					</li>
				  </ul>
				</div>
			  </div>
			</div>
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
