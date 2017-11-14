<?php

class StatusController extends Controller
{

	// // ログインが必要なActionを記述
	protected $auth_actions = array('index', 'post');

	public function indexAction()
	{
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

		// 検索文言があればsessionに値を入れ、今回と前回を比較、新規文言入力である場合のみ
		// $usersArray = 'searchWord' とさせる
		$lastSearchWord = $this->session->get('searchWord');
		$searchWord = htmlspecialchars($this->request->getPost('searchWord'));
		if (!empty($searchWord)) {
			$searchWord = mb_substr($searchWord, 0, 20, "UTF-8");
			$this->session->set('searchWord', $searchWord);
			if ($lastSearchWord !== $searchWord) {
				$usersArray = 'searchWord';
			}
		}
		if ($usersArray !== 'searchWord' || $searchWord == null || $searchWord == $lastSearchWord) {
			$this->session->remove('searchWord');
		}

		// ユーザー画面の並べ方に基づき 該当表示件数、optionタグ内selected、null時文言を返す
		list($tableCount, $selected, $usersNullMessage, $usersArrayMessage) = $this->usersArrayInfo($usersArray, $usNo, $searchWord);

		if ($tableCount == 0) {
			$page = null;
			$order = null;
			$statuses = null;
		} else {
			$offset = $this->pager($page);
			$statuses = $this->switchUsersArray($usersArray, $usNo, $offset, $order, $searchWord);
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
			'hostName' => $_SERVER['HOST_NAME'],
			'wsPort' => $_SERVER['WS_PORT'],
			'wsProtocol' => $_SERVER['WS_PROTOCOL'],
			'body' => '',
			'_token' => $this->generateCsrfToken('status/post'),
			'follow_token' => $this->generateCsrfToken('ajaxPost/follow'),
			'click_token' => $this->generateCsrfToken('ajaxPost/clickPost'),
			'user' => $user,
			'viewUser' => $viewUser,
			'headerUser' => $headerUser,
			'myStatus' => $headerUser,
			'usersArray' => $usersArray,
			'statuses' => $statuses,
			'clickStatus' => $clickStatus,
			'tableCount' => $tableCount,
			'page' => $page,
			'limit' => $this->userViewLimit,
			'postSecond' => $this->postSecond,
			'order' => $order,
			'selected' => $selected,
			'searchWord' => $searchWord,
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
