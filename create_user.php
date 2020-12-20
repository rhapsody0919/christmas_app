<?php
session_start();

require_once (dirname(__FILE__). '/function.php');
unloginedSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// バリデーションチェック
	$error_messages = [];
	if (!isset($_POST['class'])) {
		$error_messages['class'] = '期を選択してください';
	} elseif ((int)$_POST['class'] > 14 || (int)$_POST['class'] === 9) {
		$error_messages['class'] = '期を選択してください';
	}
	if (empty($_POST['name'])) {
		$error_messages['name'] = '※ニックネームを入力してください';
	} elseif (mb_strlen($_POST['name']) > 25) {
		$error_messages['name'] = '※ニックネームは25文字以下で入力してください';
	}
	if (empty($_POST['password1'])) {
		$error_messages['password1'] = '※パスワードを入力してください';
	} elseif (!preg_match(('/^[0-9a-zA-Z]+$/'), $_POST['password1'])) {
		$error_messages['password1'] = '※半角英数字で入力してください';
	} elseif (mb_strlen($_POST['password1']) > 100 || mb_strlen($_POST['password1']) < 8) {
		$error_messages['password1'] = '※パスワードは8文字以上100文字以下で入力してください';
	} elseif (empty($_POST['password2'])) {
		$error_messages['password2'] = '※確認用パスワードを入力してください';
	} elseif ($_POST['password1'] !== $_POST['password2']) {
		$error_messages['password2'] = '※パスワードが一致しません';
	}
	if (empty($_POST['slack_id'])) {
		$error_messages['slack_id'] = '※SlackIDを入力してください';
	} elseif (!preg_match(('/^[0-9a-zA-Z]+$/'), $_POST['slack_id'])) {
		$error_messages['slack_id'] = '※半角英数字で入力してください';
	} elseif (mb_strlen($_POST['slack_id']) > 20) {
		$error_messages['slack_id'] = '※SlackIDは20文字以下で入力してください';
	}
	if (empty($error_messages)) {
		$class = (int)$_POST['class'];
		$name = $_POST['name'];
		$password = password_hash($_POST['password1'], PASSWORD_DEFAULT);
		$slack_id = $_POST['slack_id'];
		$dbh = dbConnect();
		$sql = 'SELECT * FROM con1_users WHERE name = :name';
		$stmt = $dbh->prepare($sql);
		$stmt->bindValue(':name', $name);
		$stmt->execute();
		$result = $stmt->fetch();
		if ($result === false) {
			$sql = 'INSERT INTO con1_users(name, class, pass, slack_id) VALUES(:name, :class, :password, :slack_id)';
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(':name', $name, PDO::PARAM_STR);
			$stmt->bindValue(':class', $class, PDO::PARAM_INT);
			$stmt->bindValue(':password', $password, PDO::PARAM_STR);
			$stmt->bindValue(':slack_id', $slack_id, PDO::PARAM_STR);
			$result = $stmt->execute();
			if ($result === false) {
				error_log('Error : insert error ' . (__FILE__));
				setFlash('error', 'システムエラー');
				header('Location: login.php');
				exit;
			}
			$sql = 'SELECT * FROM con1_users WHERE name = :name';
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(':name', $name);
			$stmt->execute();
			$user = $stmt->fetch();
			$_SESSION['id'] = $user['id'];
			$_SESSION['name'] = $user['name'];
			$_SESSION['class'] = $user['class'];
			$_SESSION['slack_id'] = $user['slack_id'];
			setFlash('flash', '登録完了。ログインしました');
			header('Location: create_mypage_form.php');
			exit;
		} else {
			$error_messages['name'] = '※ニックネームは既に使用されています';
		}
	}
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
<?php if($_SESSION): ?>
          <li class="nav-item">
          <a class="nav-link active" href="mypage.php">
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
				  <div class="card-header border-bottom">
					<h6 class="m-0">ユーザー新規登録</h6>
				  </div>
				  <ul class="list-group list-group-flush">
					<li class="list-group-item p-3">
					  <div class="row">
						<div class="col">

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
<?php endif; ?>

<form action="create_user.php" method="post">
<label>期選択<br>
<select name="class">
<?php for ($i=1; $i<=14; $i++) : ?>
<?php if ($i === 9) continue; ?>
<option value="<?php echo $i; ?>" <?php if (isset($_POST['class']) && (int)$_POST['class'] === $i) : ?>selected<?php endif; ?>><?php echo $i; ?>期生</option>
<?php endfor; ?>
<option value="0" <?php if (isset($_POST['class']) && (int)$_POST['class'] === 0) : ?>selected<?php endif; ?>>運営</option>
</select>
</label>
<br>
<p><?php if (!empty($error_messages['class'])) echo $error_messages['class']; ?></p>
<label>ニックネーム<small>&nbsp;※25文字以下</small><br>
<input type="text" name="name" value="<?php if (!empty($_POST['name'])) echo $_POST['name']; ?>" required>
</label><br>
<p><?php if (!empty($error_messages['name'])) echo $error_messages['name']; ?></p>
<label>パスワード<small>&nbsp;※8文字以上半角英数字</small><br>
<input type="password" name="password1" value="<?php if (!empty($_POST['password1'])) echo $_POST['password1']; ?>" required>
</label><br>
<p><?php if (!empty($error_messages['password1'])) echo $error_messages['password1']; ?></p>
<label>パスワード（確認用）<br>
<input type="password" name="password2" required>
</label><br>
<p><?php if (!empty($error_messages['password2'])) echo $error_messages['password2']; ?></p>
<label>SlackID<br>
<input type="text" name="slack_id" value="<?php if (!empty($_POST['slack_id'])) echo $_POST['slack_id']; ?>" required>
</label><br>
<p><?php if (!empty($error_messages['slack_id'])) echo $error_messages['slack_id']; ?></p>
<p><strong>SlackIDとは?</strong><br>Slackのシステム側でユーザーを一意に管理するために付与されたシステム用のIDです。<br>
プロサーの<a href="https://procir.site/user/edit" target="_blank">プロフィール編集画面</a>から確認できます。
<br>プロサーにSlackIDを登録していない方は、<a href="https://procir.site/contact/detail/94">SlackのメンバーID設定方法</a>をご覧ください。</p>
<input type="submit" value="登録する">
</form><br>

						</div>
					  </div>
					</li>
				  </ul>
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
