<?php

class HistoryController extends Controller
{

	// ログインが必要なActionを記述
	// protected $auth_actions = array('userHistory');

	// ページ送り、 期間変更 並び順変更 1ページ辺りの件数変更

	// サービス全体での履歴を出力
	public function GeneralAction()
	{
		$this->serviceStatus();
		$usersArray = strval($this->request->getGet('usersArray'));
		$usersArray = htmlspecialchars($usersArray, ENT_QUOTES);
		if (empty($usersArray)) {
			$usersArray = 'general';
		}

		$order = $this->getOrder();
		$page = $this->getPager();

		$tableCount = $this->db_manager->get('Admin')->tableCount('tbgvn');
		$tableCount = intval($tableCount['tbgvn']);
		$limit = 50;
		$offset = $page * $limit;

		$historyArray = $this->db_manager->get('History')->fetchAllUsersHistry($limit, $offset, $order);

		$result = $this->pointRounder($historyArray, $action = 'general');

		return $this->render(array(
			'result' => $result,
			'page' => $page,
			'limit' => $limit,
			'tableCount' => $tableCount,
			'order' => $order,
			'usersArray' => $usersArray,
			'calcCount' => $this->calcCount,
		),'general');
	}

	// 特定のユーザーの送信・受信履歴を出力
	public function userHistoryAction()
	{
		// **** uesrID検索実装する際に使えそう*****
		// $viewUser = strval($this->request->getGet('userID'));
		// $viewUser = htmlspecialchars($viewUser, ENT_QUOTES);
		// if (empty($viewUser)) {
		// 	// ユーザーが指定されていません。
		// }
		// if (!preg_match('/^\w{3,20}$/', $viewUser)) {
		// 	// ユーザーIDが長すぎ、または短すぎです。
		// }
		// if (!preg_match("/^[a-zA-Z0-9]+$/", $viewUser)) {
		// 	// すべて半角英数ではありません。
		// }
		$path = dirname(__FILE__) . '/../../../hidden/info.php';
		require $path;
		$this->serviceStatus();

		$user = $this->session->get('user');
		$usNo = intval($user['usNo']);

		$viewUser = $this->request->getGet('viewUser');
		$viewUser = htmlspecialchars($viewUser, ENT_QUOTES);
		if (empty($viewUser)) {
			// ユーザーが指定されていません。
			$viewUser = $usNo;
		}
		if (is_array($viewUser)) {
			// 不正なデータ（配列）です
			$viewUser = $usNo;
		}
		$viewUser = intval($viewUser);
		// if ($viewUser == 0) {
		// 	// 不正なリクエストです
		// 	$viewUser = $usNo;
		// }
		$headerUser = $this->headerUserPerson($viewUser, $usNo, $this->lastCalcTime);
		$headerUser = $this->pointRounder($headerUser, $action = 'index');

		$myStatus = $this->headerUserPerson($usNo, $usNo, $this->lastCalcTime);
		$myStatus = $this->pointRounder($myStatus, $action = 'index');

		$usersArray = strval($this->request->getGet('usersArray'));
		$usersArray = htmlspecialchars($usersArray, ENT_QUOTES);

		if ($usersArray === 'toSendHistory') {
			$usersArray = 'toSendHistory';
		}
		elseif ($usersArray === 'receiveFromHistory') {
			$usersArray = 'receiveFromHistory';
		}
		elseif (empty($usersArray)) {
			$usersArray = 'receiveFromHistory';
		}
		else {
			// 値が正しくなければもらった履歴として処理
			$usersArray = 'receiveFromHistory';
		}

		$order = $this->getOrder();
		$page = $this->getPager();

		// ユーザー画面の並べ方に基づき 該当表示件数、optionタグ内selected、null時文言を返す
		list($tableCount, $selected, $usersNullMessage, $usersArrayMessage) = $this->usersArrayInfo($usersArray, $viewUser);
		$tableCount = intval($tableCount);

		$limit = 50;
		$offset = $page * $limit;

		$historyArray = $this->db_manager->get('History')->fetchAllUsersHistry($limit, $offset, $order, $viewUser, $usersArray);

		$result = $this->pointRounder($historyArray, $action = 'general');

		$clickStatus = $this->db_manager->get('Status')->fetchClickStatus($usNo, $this->lastCalcTime);

		return $this->render(array(
			'hostName' => $hostName,
			'wsPort' => $wsPort,
			'wsProtocol' => $wsProtocol,
			'myStatus' => $myStatus,
			'viewUser' => $viewUser,
			'tableCount' => $tableCount,
			'selected' => $selected,
			'user' => $user,
			'usersNullMessage' => $usersNullMessage,
			'usersArrayMessage' => $usersArrayMessage,
			'headerUser' => $headerUser,
			'_token' => $this->generateCsrfToken('history/userHistory'),
			'follow_token' => $this->generateCsrfToken('ajaxPost/follow'),
			'click_token' => $this->generateCsrfToken('ajaxPost/clickPost'),
			'clickStatus' => $clickStatus,
			'postSecond' => $this->postSecond,
			'result' => $result,
			'page' => $page,
			'limit' => $limit,
			'order' => $order,
			'usersArray' => $usersArray,
			'calcCount' => $this->calcCount,
		),'userHistory');
	}


	// 集計日毎のPtの移り変わりをまとめた履歴
	// 集計日毎の送信比率をまとめた履歴　他人に送ったPt / 自分にキャッシュしたPt
	// 集計日毎の受信比率をまとめた履歴　他人に送ったPt / もらったPt

}
