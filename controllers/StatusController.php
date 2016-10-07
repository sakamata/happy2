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
// var_dump($headerUser);
// var_dump($statuses[0]);

		return $this->render(array(
			'hostName' => $hostName,
			'wsPort' => $wsPort,
			'wsProtocol' => $wsProtocol,
			'body' => '',
			'_token' => $this->generateCsrfToken('status/post'),
			'follow_token' => $this->generateCsrfToken('ajaxPost/follow'),
			'click_token' => $this->generateCsrfToken('ajaxPost/clickPost'),
			'user' => $user,
			'viewUser' => $viewUser,
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

	public function releaseNewsAction()
	{
		return $this->render(array(

		));
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

}
