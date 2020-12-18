<?php
require_once (dirname(__FILE__) . '/../zoom_api/get_participants_count.php');
require_once (dirname(__FILE__). '/../function.php');
require_once (dirname(__FILE__). '/../slack_api/slack_notification.php');

function getBothJoinedRate() {
	//matghing_usersテーブルから全てのzoom_uuidを取得
	$all_zoom_uuid = getAllZoomUUID();
	$all_zoom_count = count($all_zoom_uuid);
	if (!$all_zoom_uuid) {
		return false;
	}
	#################################################
	//臨時テスト
	$participants_count = getParticipantsCount('cKeQOMApR4u/XiKywjkZLA==');
	$both_joined_count = 0;
	if ($participants_count >= 2) {
		$both_joined = 1;
		$both_joined_count += 1;
	//参加者が1人未満の場合
	} else {
		$both_joined = 0;
	}
	$both_joined_rate = round($both_joined_count / 1, 1);
	return [1, $both_joined_count, $both_joined_rate];
	################################################

	//それぞれのzoom uuidから参加人数を取得
	/*
	foreach ($all_zoom_uuid as $zoom_uuid) {
		if (!getParticipantsCount($zoom_uuid['zoom_uuid'])) {
			return false;
		}
		$participants_count = getParticipantsCount($zoom_uuid['zoom_uuid']);
		//参加者が２人以上の場合
		if ($participants_count >= 2) {
			$both_joined = 1;
			$both_joined_count += 1;
		//参加者が1人未満の場合
		} else {
			$both_joined = 0;
		}
		updateBothJoinedByUuid($both_joined, $zoom_uuid['zoom_uuid']);
	}
	$both_joined_rate = round($both_joined_count / $all_zoom_count, 1);
	//面談総数、面談された数、面談率を返却
	return [$all_zoom_count, $both_joined_count, $both_joined_rate];
	 */
}

//zoom面談率を取得できない場合
if (!getBothJoinedRate()) {
	error_log('無効なuuidが入力されました。zoom面談率を算出できませんでした。' . "\n", 3, __DIR__ . '/../log/both_joined_rate.log');
//面談総数、面談された数、面談率を返却された場合
} elseif (getBothJoinedRate()) {
	list($all_zoom_count, $both_joined_count, $both_joined_rate) = getBothJoinedRate();
	//slack通知
	$message = 'マッチングして実際にzoomミーティングが行われた割合は、' . $both_joined_rate . '%でした!' . "\n" .
		'[内訳 : マッチングペア数:' . $all_zoom_count . '組 ' . 'zoomミーティングが行われたペア数:' . $both_joined_count . '組]';
	slackNotification($message);
}


