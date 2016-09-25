<?php

class HistoryController extends Controller
{

	// ログインが必要なActionを記述
	// protected $auth_actions = array();

	// ページ送り、 期間変更 並び順変更 1ページ辺りの件数変更

	// サービス全体での履歴を出力
	public function GeneralAction()
	{
		$usersArray = strval($this->request->getGet('usersArray'));
		$usersArray = htmlspecialchars($usersArray, ENT_QUOTES);
		if (empty($usersArray)) {
			$usersArray = 'general';
		}

		$order = strval($this->request->getGet('order'));
		$order = htmlspecialchars($order, ENT_QUOTES);
		if ((!$order == 'ASC') || (!$order == 'DESC')) {
			$order = 'DESC';
		}

		$page = $this->request->getGet('pager');
		$page = htmlspecialchars($page, ENT_QUOTES);
		$page = intval($page);	// 空や不正な値は全て int 0 を返す　配列なら1

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
		),'general');
	}


	// 特定のユーザーの送信履歴を出力
	// 特定のユーザーの受信履歴を出力

	// 特定のユーザーの送信をユーザー毎にまとめた履歴
	// 特定のユーザーの受信をユーザー毎にまとめた履歴

	// 集計日毎のPtの移り変わりをまとめた履歴
	// 集計日毎の送信比率をまとめた履歴　他人に送ったPt / 自分にキャッシュしたPt
	// 集計日毎の受信比率をまとめた履歴　他人に送ったPt / もらったPt

}
