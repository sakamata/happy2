<?php

class StatusController extends Controller
{

	// // ログインが必要なActionを記述
	protected $auth_actions = array('index', 'post');

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
		$headerUser = $this->pointRounder($headerUser, $action = 'index');

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
		list($tableCount, $selected, $usersNullMessage, $usersArrayMessage) = $this->usersArrayInfo($usersArray, $usNo);

		if ($tableCount == 0) {
			$page = null;
			$order = null;
			$statuses = null;
		} else {
			$offset = $this->pager($page);
			$statuses = $this->switchUsersArray($usersArray, $usNo, $offset, $order);
			$statuses = $this->pointRounder($statuses, $action = 'index');
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
			'wsProtocol' => $wsProtocol,
			'body' => '',
			'_token' => $this->generateCsrfToken('status/post'),
			'follow_token' => $this->generateCsrfToken('ajaxPost/follow'),
			'click_token' => $this->generateCsrfToken('ajaxPost/clickPost'),
			'user' => $user,
			'headerUser' => $headerUser,
			'usersArray' => $usersArray,
			'statuses' => $statuses,
			'clickStatus' => $clickStatus,
			'tableCount' => $tableCount,
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
				$tableCount = $this->db_manager->get('Status')->countFollowing($usNo);
				$tableCount = $tableCount['tableCount'];

				$selected['following'] = 'selected';
				$usersNullMessage = "フォロー中のユーザーはまだいません。";
				$usersArrayMessage = "フォロー中の";
				return array($tableCount, $selected, $usersNullMessage, $usersArrayMessage);
				break;

			case 'followers':
				$tableCount = $this->db_manager->get('Status')->countFollowers($usNo);
				$tableCount = $tableCount['tableCount'];

				$selected['followers'] = 'selected';
				$usersNullMessage = "フォローされているユーザーはまだいません。";
				$usersArrayMessage = "フォローされている";
				return array($tableCount, $selected, $usersNullMessage, $usersArrayMessage);
				break;

			case 'test':
				$selected['test'] = 'selected';
				$tableCount = 0;
				$usersNullMessage = "testメッセージ　ユーザーはまだいません。";
				$usersArrayMessage = "testをしているユーザー";
				return array($tableCount, $selected, $usersNullMessage, $usersArrayMessage);
				break;

			default:
				// newUsers 新規ユーザー順 user数を返す
				$tableName = 'tbus';
				$tableCount = $this->db_manager->get('Admin')->tableCount($tableName);
				$tableCount = $tableCount['tbus'];
				$selected['newUsers'] = 'selected';
				$usersNullMessage = "他のユーザーはまだいません。";
				$usersArrayMessage = "登録順";
				return array($tableCount, $selected, $usersNullMessage, $usersArrayMessage);
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

}
