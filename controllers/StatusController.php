<?php

class StatusController extends Controller
{

	// ログインが必要なActionを記述
	protected $auth_actions = array('index', 'post');
	protected $lastCalcTime;	// 最終集計時間
	protected $allUserCount;	// 全ユーザー数
	protected $userViewLimit; // ユーザー表示数
	protected $calcCount; // 集計回数
	protected $myUser; // tbusステータス

	public function indexAction()
	{
		$this->serviceStatus();
		// $this->userPerson();
		$user = $this->session->get('user');
		// $statuses = $this->db_manager->get('Status')->fetchUserStatus($user['usNo']);

		$limit = 10;
		$offset = 2;

		// ToDo ユーザー画面の並べ方を取得、該当表示件数を返す(POSTデフォならデフォ表示)


		$statuses = $this->db_manager->get('Status')->testUsersArrayFollowUsers($user['usNo'], $this->lastCalcTime, $limit, $offset, $order);

		var_dump($user);
		var_dump($statuses[0]);

		return $this->render(array(
			'statuses' => $statuses,
			'body' => '',
			'_token' => $this->generateCsrfToken('status/post'),
		));
	}

	// サービス全体で必要な情報を生成
	public function serviceStatus()
	{
		$allUserCount = $this->db_manager->get('Admin')->tableCount('tbus');
		$this->allUserCount = $allUserCount['tbus'];
		$adsetting = $this->db_manager->get('AdminSetting')->fetchSettingValue();
		$this->userViewLimit = $adsetting['userViewLimitClients'];
		$calcStatus = $this->db_manager->get('Status')->calcStatus();
		$this->calcCount = $calcStatus['calcCount'];
		$this->lastCalcTime = $calcStatus['lastCalcTime'];
	}

	// 自分のアカウントを元に、自分に関する情報を生成
	public function anyUserPerson()
	{
		// tbus情報
		// 今回のこの人からのクリック数（グラフ）
		// 今回のこの人が押したクリック数（グラフ）
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
	public function userPerson($userNo)
	{
		// （仮）
		$this->myUser = $this->session->get('user');
	}

	public function userAction($params)
	{
		$user = $this->db_manager->get('User')->fetchByUserName($params['usName']);

		if (!$user) {
			$this->forward404();
		}

		$statuses = $this->db_manager->get('Status')->fetchAllByUserId($user['usNo']);

		$following = null;
		if ($this->session->isAuthenticated()) {
			$my = $this->session->get('user');
			if ($my['usNo'] !== $user['usNo']) {
				$following = $this->db_manager->get('Following')->isFollowing($my['usNo'], $user['usNo']);
			}
		}

		return $this->render(array(
			'user' => $user,
			'statuses' => $statuses,
			'following' => $following,
			'_token' => $this->generateCsrfToken('account/follow'),
		));

	}

	public function showAction($params)
	{
		$status = $this->db_manager->get('Status')->fetchByIDAndUserName($params['usNo'], $params['usName']);

		if (!$status) {
			$this->forward404();
		}

		return $this->render(array('status' => $status));

	}

}
