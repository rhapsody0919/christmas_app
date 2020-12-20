<?php
require_once (dirname(__FILE__) . '/../vendor/autoload.php');
require_once (dirname(__FILE__). '/../function.php');
require_once (dirname(__FILE__). '/../slack_api/slack_notification.php');

function matching() {
	try {
		$dbh = dbConnect();
		//マッチングありに指定しているuserをDBから取得できない場合
		if (!getMatchingOnUsers()) {
			error_log('マッチングありなuserを取得できませんでした。' . "\n", 3, __DIR__ . '/../log/matching.log');
			error_log($msg . "\n", 3, __DIR__ . '/../log/matching.log');
			return false;
		}
		//マッチングありに指定しているuserをDBから取得
		$matching_on_users = getMatchingOnUsers();
		//userの数を取得
		$matching_on_users_count = count($matching_on_users);

		//奇数の場合
		if ($matching_on_users_count % 2 !== 0) {
			//マッチングOFFにしている運営ユーザのmatchingをONにupdate
			if (!updateMatchingOn('terukina')) {
				error_log('マッチングOFFにしているterukinaをマッチングONにできませんでした。' . "\n", 3, __DIR__ . '/../log/matching.log');
				error_log($msg . "\n", 3, __DIR__ . '/../log/matching.log');
				return false;
			}
			//マッチングありに指定しているuserをDBから取得できない場合
			if (!getMatchingOnUsers()) {
				error_log('マッチングありなuserを取得できませんでした。' . "\n", 3, __DIR__ . '/../log/matching.log');
				error_log($msg . "\n", 3, __DIR__ . '/../log/matching.log');
				return false;
			}
			//マッチングありに指定しているuserをDBから取得
			$matching_on_users = getMatchingOnUsers();
		}
		$matching_on_users_count = count($matching_on_users);

		shuffle($matching_on_users);
		while ($matching_on_users) {
			//ランダムに2人ずつ取得して配列から削除
			$selected_users = array_splice($matching_on_users, 0, 2);
			$query[] = '(?, ?)';

			$param[] = $selected_users[0]['id'];
			$param[] = $selected_users[1]['id'];
		}
		//DBに保存
		$insert_sql = 'INSERT INTO con1_matching_users (user_id_1, user_id_2) VALUES ';
		$insert_sql .= implode(', ', $query);
		$insert_stm = $dbh->prepare($insert_sql);
		$insert_stm->execute($param);

		return true;

	} catch (PDOException $e) {
		$msg = 'Error : ' . $e->getMessage();
		error_log($msg . "\n", 3, __DIR__ . '/../log/matching.log');
		return false;
	}
}

//成功した場合
if (matching()) {
	error_log('マッチングに成功しました。' . "\n", 3, __DIR__ . '/../log/matching.log');
	$matching_results = getMatchingResults();
	//マッチング結果をスラック通知
	foreach ($matching_results as $matching_result) {
		$user_slack_id1 = getUserById($matching_result['user_id_1'])['slack_id'];
		$user_slack_id2 = getUserById($matching_result['user_id_2'])['slack_id'];
		$message = '「プロサーがサンタクロース」 -ボトルメッセージから始まる新しいつながり-' . "\n" .
			"<@$user_slack_id1>" . 'さんと' . "<@$user_slack_id2>" . 'さんがマッチングしました!' . "\n" .
			'早速DMやzoomでお話ししてみよう!';
		slackNotification($message);
	}

} else {
	error_log('マッチングに失敗しました。' . "\n", 3, __DIR__ . '/../log/matching.log');
}

