<?php
class AjaxPostController extends Controller
{

	// ログインが必要なActionを記述登録
	protected $auth_actions = array(
		'clickPost',
		'follow'
	);

	public function clickPostAction()
	{
		$token = strval($this->request->getPost('click_token'));
		if (!$this->checkCsrfTokenLasting('ajaxPost/clickPost', $token)) {
			error_log('Error!! click_token check!!');
			return;
		}

		$user = $this->session->get('user');
		$usNo = intval($user['usNo']);
		$postDateTime = strval($this->request->getPost('postDateTime'));
		$clicks = $this->request->getPost('clicks');

		$clicks = $this->postTimeAdjustment($clicks, $postDateTime);
		$this->db_manager->get('User')->clickPost($usNo, $clicks);
	}

	// POST時間の修正
	// ユーザー側のTime stamp をサーバー側の時間軸基準に修正
	// POSTのintavalより大きな値（過去や未来）の時間があった場合は、現在時刻とする
	// *** ToDo *** POST期間が最終集計時間と重複した場合は、該当のPOST時間を最終集計時間以降に変更してPOST
	public function postTimeAdjustment($clicks, $postDateTime)
	{
		$date = new DateTime();
		$nowTimeStamp = $date->format('Y-m-d H:i:s');
		$adsetting = $this->db_manager->get('AdminSetting')->fetchSettingValue();
		$interval = $adsetting['userClickPostIntervalSecond'];
		$postInterval = intval($interval);

		// ユーザー側時刻との誤差（秒単位）
		$postTremSecond = strtotime($postDateTime) - strtotime($nowTimeStamp);
		$systemLastPostTime = date('Y-m-d H:i:s', strtotime("$nowTimeStamp - $postInterval second"));
		$admin_repository = $this->db_manager->get('Admin');
		$last = $admin_repository->lastCalcTime();
		$lastCalcTime = $last['date'];

		// ユーザー側のTime stamp をサーバー側の時間軸基準に修正
		// クライアントの日時誤差の補正
		$a = 0;
		while ($a < count($clicks)) {
			$no = 'no_' . $a;
			$userTimestamp = $clicks[$no]['timestamp'];

			if ( strtotime($postDateTime) > strtotime($nowTimeStamp) ) { // 未来なら
				$numeric = '-';
				$trem = $postTremSecond;
			} elseif ( strtotime($postDateTime) <= strtotime($nowTimeStamp) ) { // 過去なら
				$numeric = '+';
				$trem = -($postTremSecond);
			}
			$revisionTimestamp = date('Y-m-d H:i:s', strtotime("$userTimestamp $numeric $trem second"));

			// クリック時間の異常・不正対策
			// 投稿時差補正しても前回POST～現在以外の日時を置換
			if ( strtotime($revisionTimestamp) > strtotime($nowTimeStamp) ) { // 未来なら
				$clicks[$no]['timestamp'] = $nowTimeStamp;
			} elseif ( strtotime($revisionTimestamp) < strtotime($systemLastPostTime) ) { // 過去なら
				$adjustLastPostTime = date('Y-m-d H:i:s', strtotime("$systemLastPostTime + 1 second"));
				$clicks[$no]['timestamp'] = $adjustLastPostTime;
			} else { // 正常なら補正時間に置換
				$clicks[$no]['timestamp'] = $revisionTimestamp;
			}
			// error_log(print_r('$revisionTimestamp',true),0);
			// error_log(print_r($revisionTimestamp,true),0);
			$a++;
		}
		return $clicks;
	}

	public function followAction()
	{
		$token = strval($this->request->getPost('f_token'));
		if (!$this->checkCsrfToken('ajaxPost/follow', $token)) {
			return;
		}
		$followingNo = intval($this->request->getPost('followingNo'));
		$user = $this->session->get('user');
		$usNo = intval($user['usNo']);
		$exist = $this->db_manager->get('Follow')->CheckFollowing($usNo, $followingNo);
		$action = strval($this->request->getPost('followAction'));

		if ($action === 'doFollow'  &&  $exist === false  &&  $usNo !== $followingNo) {
			$this->db_manager->get('Follow')->following($usNo, $followingNo);
		}

		if ($action === 'unFollow'  &&  $exist === true  &&  $usNo !== $followingNo) {
			$this->db_manager->get('Follow')->unFollow($usNo, $followingNo);
		}
	}

}
