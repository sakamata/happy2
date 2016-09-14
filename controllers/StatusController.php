<?php

class StatusController extends Controller
{

	// ログインが必要なActionを記述
	protected $auth_actions = array('index', 'post');
	protected $lastCalcTime;	// 最終集計時間
	protected $allUserCount;	// 全ユーザー数
	protected $userViewLimit; // ユーザー表示数
	protected $postSecond; // クリック情報の定期POST秒
	protected $calcCount; // 集計回数
	protected $myUser; // tbusステータス

	// サービス全体で必要な情報を生成
	public function serviceStatus()
	{
		$allUserCount = $this->db_manager->get('Admin')->tableCount('tbus');
		$this->allUserCount = $allUserCount['tbus'];
		$adsetting = $this->db_manager->get('AdminSetting')->fetchSettingValue();
		$limit = $adsetting['userViewLimitClients'];
		$this->userViewLimit = intval($limit);
		$postSecond = $adsetting['userClickPostIntervalSecond'];
		$this->postSecond = intval($postSecond);

		$calcStatus = $this->db_manager->get('Status')->calcStatus();
		$this->calcCount = $calcStatus['calcCount'];
		$this->lastCalcTime = $calcStatus['lastCalcTime'];
	}

	public function indexAction()
	{
		$path = dirname(__FILE__) . '/../../../hidden/info.php';
		require $path;

		$this->serviceStatus();
		$user = $this->session->get('user');
		$usNo = $user['usNo'];
		$viewUser = $user['usNo'];

		$clickStatus = $this->db_manager->get('Status')->fetchClickStatus($usNo, $this->lastCalcTime);
		$array = [];
		$headerUser = $this->headerUserPerson($viewUser, $usNo, $this->lastCalcTime);
		$headerUser = $this->pointRounder($headerUser);

		$usersArray = strval($this->request->getPost('usersArray'));
		if (empty($usersArray)) {
			$usersArray = 'newUsers';
		}
		$order = strval($this->request->getPost('order'));
		if (empty($order)) {
			$order = 'DESC';
		}
		$page = intval($this->request->getPost('pager'));
		if (empty($page)) {
			$page = 0;
		}

		// ユーザー画面の並べ方に基づき 該当表示件数、optionタグ内selected、null時文言を返す
		list($userCount, $selected, $usersNullMessage, $usersArrayMessage) = $this->usersArrayInfo($usersArray, $usNo);

		if ($userCount == 0) {
			$page = null;
			$order = null;
			$statuses = null;
		} else {
			$offset = $this->pager($page, $userCount);
			$statuses = $this->switchUsersArray($usersArray, $usNo, $offset, $order);
			$statuses = $this->pointRounder($statuses);
		}

		// Cookieの値による画面表示切り替え
		$viewType = null;
		if (isset($_COOKIE["viewType"])) {
			if (htmlspecialchars($_COOKIE["viewType"]) == 'small') {
				$viewType = 'index_small';
			}
		}

		return $this->render(array(
			'hostName' => $hostName,
			'wsPort' => $wsPort,
			'body' => '',
			'_token' => $this->generateCsrfToken('status/post'),
			'follow_token' => $this->generateCsrfToken('ajaxPost/follow'),
			'click_token' => $this->generateCsrfToken('ajaxPost/clickPost'),
			'user' => $user,
			'headerUser' => $headerUser,
			'usersArray' => $usersArray,
			'statuses' => $statuses,
			'clickStatus' => $clickStatus,
			'userCount' => $userCount,
			'page' => $page,
			'limit' => $this->userViewLimit,
			'postSecond' => $this->postSecond,
			'order' => $order,
			'selected' => $selected,
			'usersNullMessage' => $usersNullMessage,
			'usersArrayMessage' => $usersArrayMessage,
			'calcCount' => $this->calcCount,
		),$viewType);
	}

	// 自分のアカウントを元に、自分に関する情報を生成
	public function anyUserPerson()
	{
		// 今回のこの人からのクリック数（グラフ）
		// 今回のこの人が押したクリック数（グラフ）
		// 今回この人からもらったクリック数（グラフ）
		// 今回この人の全クリック数(グラフ)
		// ログイン中か（簡易スタータス）
		// フォロー関係（簡易スタータス）
		// SimpleUsersPersons() 単数取得
	}

	// マウスオン時簡易ステータス
	public function SimpleUsersPersons()
	{
		// DailyAvg
		// Day
		// NarcisRate
		// Total

	}

	// 任意のユーザー1名の基本情報を生成
	// 『メイン画面』や『履歴画面 他人』のヘッダー等に使用
	public function headerUserPerson($viewUser, $usNo, $lastCalcTime)
	{
		$res = $this->db_manager->get('Status')->fetchHeaderUserPerson($viewUser, $usNo, $lastCalcTime);
		return $res;
	}

	public function usersArrayInfo($usersArray, $usNo)
	{
		$selected = array(
			'newUsers' => null,
			'following' => null,
			'followers' => null,
			'test' => null,
		);

		switch ($usersArray) {
			case 'following':
				$userCount = $this->db_manager->get('Status')->countFollowing($usNo);
				$userCount = $userCount['userCount'];

				$selected['following'] = 'selected';
				$usersNullMessage = "フォロー中のユーザーはまだいません。";
				$usersArrayMessage = "フォロー中の";
				return array($userCount, $selected, $usersNullMessage, $usersArrayMessage);
				break;

			case 'followers':
				$userCount = $this->db_manager->get('Status')->countFollowers($usNo);
				$userCount = $userCount['userCount'];

				$selected['followers'] = 'selected';
				$usersNullMessage = "フォローされているユーザーはまだいません。";
				$usersArrayMessage = "フォローされている";
				return array($userCount, $selected, $usersNullMessage, $usersArrayMessage);
				break;

			case 'test':
				$selected['test'] = 'selected';
				$userCount = 0;
				$usersNullMessage = "testメッセージ　ユーザーはまだいません。";
				$usersArrayMessage = "testをしているユーザー";
				return array($userCount, $selected, $usersNullMessage, $usersArrayMessage);
				break;

			default:
				// newUsers 新規ユーザー順 user数を返す
				$tableName = 'tbus';
				$userCount = $this->db_manager->get('Admin')->tableCount($tableName);
				$userCount = $userCount['tbus'];
				$selected['newUsers'] = 'selected';
				$usersNullMessage = "他のユーザーはまだいません。";
				$usersArrayMessage = "登録順";
				return array($userCount, $selected, $usersNullMessage, $usersArrayMessage);
				break;
		}
	}

	public function switchUsersArray($usersArray, $usNo, $offset, $order)
	{
		$lastCalcTime = $this->lastCalcTime;
		$limit = $this->userViewLimit;

		switch ($usersArray) {
			case 'following':
				$statuses = $this->db_manager->get('Status')->usersArrayFollowingUsers($usNo, $lastCalcTime, $limit, $offset, $order);
				return $statuses;
				break;

			case 'followers':
				$statuses = $this->db_manager->get('Status')->usersArrayFollowersUsers($usNo, $lastCalcTime, $limit, $offset, $order);
				return $statuses;
				break;

			default:
				// newUsers
				$statuses = $this->db_manager->get('Status')->usersArrayNewUsers($usNo, $lastCalcTime, $limit, $offset, $order);
				return $statuses;
				break;
		}
	}

	public function pager($page, $userCount)
	{
		$limit = $this->userViewLimit;
		$offset = $page * $limit;
		return $offset;
	}

	public function pointRounder($users)
	{
		if(array_key_exists('nowPt', $users)) {
			$nowPt = $users['nowPt'];
			$nowPt = floatval($nowPt);
			$nowPt = round($nowPt, 2);
			$users['roundPt'] = strval($nowPt);
		} else {
			$i = 0;
			foreach ($users as $user) {
				$nowPt = $user['nowPt'];
				$nowPt = floatval($nowPt);
				$nowPt = round($nowPt, 2);
				$users[$i]['roundPt'] = strval($nowPt);
				$i++;
			}
		}
		return $users;
	}

}
