<?php
class AdminController extends Controller
{
	public $tableNames = array('tbus', 'tbgvn', 'tbset', 'tbfollow', 'tbcalctime');
	public $commands = array('Reset', 'DummyIn', 'DummysIn');

	public function indexAction()
	{
		$session = $this->session->get('admin');
		$admin_repository = $this->db_manager->get('Admin');
		$adsetting_repository = $this->getAdminSetting();

		$setting = $adsetting_repository->fetchSettingValue();
		$limit = $setting['adminTablesViewLimit'];

		// ToDo pager機能の分離
		$tables = [];
		$tbCounts = [];
		$key = [];
		$pages = [];
		$offsets = [];

		foreach ($this->tableNames as $tableName) {

			$tb = $this->request->getGet('table');
			if ($tb == $tableName) {
				$pages[$tableName] = $this->request->getGet('page');
			}

			if (empty($pages[$tableName])) {
				$pages[$tableName] = 0;
			}
			$nextpages[$tableName] = $pages[$tableName] + 1;
			$prevpages[$tableName] = $pages[$tableName] - 1;
			$offsets[$tableName] = $limit * $pages[$tableName];

			if ($tableName == 'tbus') {
				// PassWord非表示の為の別処理
				$tables[$tableName] = $admin_repository->fetchAlltbus($limit, $offsets[$tableName]);
			} else {
				$tables[$tableName] = $admin_repository->fetchAllTable($tableName, $limit, $offsets[$tableName]);
			}

			if (!$admin_repository->tableCount($tableName)) {
				$key = array_merge($key, array($tableName => 'no Table!'));
			} else{
				$key = $admin_repository->tableCount($tableName);
			}
			$tbCounts += array($tableName => $key[$tableName]);
		}

		return $this->render(array(
			'body' => '',
			'tableNames' => $this->tableNames,
			'commands' => $this->commands,
			'tables' => $tables,
			'tbCounts' => $tbCounts,
			'pages' => $pages,
			'offsets' => $offsets,
			'nextpages' => $nextpages,
			'prevpages' => $prevpages,
			'limit' => $limit,
			'_token' => $this->generateCsrfToken('admin/post'),
		));
	}

	// table操作を集約 Reset,DummyIn を各テーブルで行わせる
	public function tableCommandAction()
	{
		$token = $this->request->getPost('_token');
		if (!$this->checkCsrfToken('admin/post', $token)) {
			return $this->redirect('/admin/index');
		}

		$tableName = $this->request->getPost('tableName');
		$command = $this->request->getPost('command');
		$dummyNames = $this->RandomMaker();

		// AdminRepossitoryのメソッド名を生成
		$RepossitoryCommnd = $tableName.$command;
		if (!method_exists('AdminRepository', $RepossitoryCommnd)) {
			$this->forward404();
		}

		$this->db_manager->get('Admin')->$RepossitoryCommnd($dummyNames);
		return $this->redirect('/admin/index#anchor_'.$tableName);
	}


	public function RandomMaker() {
		$length = 8;

		//Thanks!! http://qiita.com/TetsuTaka/items/bb020642e75458217b8a
		static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
		$str = '';
		for ($i = 0; $i < $length; ++$i) {
			$str .= $chars[mt_rand(0, 61)];
		}

		// Thanks!! http://mitsuakikawamorita.com/blog/?p=1095
		$hiragana = 'あいうえおかきくけこさしすせそたちつてとなにぬねのはひふへほまみむめもやゆよらりるれろわおん';
		$kanji = '右雨王音火貝九玉金月犬見口左山子糸耳車手十女人水夕石川早足大竹虫天田土日年白文木目立力六一ニ三四五下七小上生中入八本円休出森正赤千男町名林花学気空校字青先草村百'; // 小学1年生で習う漢字

		$jp_literals = $hiragana . $kanji;
		$usName = "";
		for ($i = 0; $i < $length; $i++) {
			$usName .= mb_substr($jp_literals, mt_rand(0, mb_strlen($jp_literals, "UTF-8") - 1), 1, "utf-8");
		}

		$usId = $str;
		$no1 = rand(1,10);
		$no2 = rand(1,10);
		$clk = rand(1,30);
		$dummys = array('no1' => $no1, 'no2' => $no2, 'clk' => $clk, 'usId' =>$usId, 'usName' =>$usName);

		return $dummys;
	}

	public function getAdminSetting()
	{
		$adsetting_repository = $this->db_manager->get('AdminSetting');
		return $adsetting_repository;
	}


	// ***ToDo***
	public function pagerAction($table, $page, $offset)
	{
		$setting = $adsetting_repository->fetchSettingValue();
		$limit = $setting['adminTablesViewLimit'];

		$page = $this->request->getGet('tbus');

		if (empty($page)) {
			$page = 0;
		}
		$nextpage = $page + 1;
		$prevpage = $page - 1;
		$offset = $limit * $page;

		$pager = array($offset, $limit, $page);
		return $pager;
	}


	public function signinAction()
	{
		if ($this->session->isAuthenticated()) {
			return $this->redirect('/admin');
		}

		return $this->render(array(
			'usId' => '',
			'usPs' => '',
			'_token' => $this->generateCsrfToken('admin/signin'),
		));
	}

	public function authenticateAction()
	{
		if ($this->session->isAuthenticated()) {
			return $this->redirect('/admin');
		}

		if (!$this->request->isPost()) {
			$this->forward404();
		}

		$token = $this->request->getPost('_token');
		if (!$this->checkCsrfToken('admin/signin', $token)) {
			return $this->redirect('/admin/signin');
		}

		$usId = $this->request->getPost('usId');
		$usPs = $this->request->getPost('usPs');

		$errors = array();

		if (!strlen($usId)) {
			$errors[] = 'ユーザーIDを入力してください';
		}

		if (!strlen($usPs)) {
			$errors[] = 'パスワードを入力してください';
		}

		if (count($errors) === 0) {

			$admin_repository = $this->db_manager->get('Admin');
			$user_repository = $this->db_manager->get('User');
			$user = $admin_repository->fetchByAdminUserName($usId);

			if (!$user || $user['usPs'] !== $user_repository->hashPassword($usPs)) {
				$errors[] = 'ユーザーIDかパスワードが正しくありません。';
			} else {
				$this->session->setAuthenticated(true);
				$this->session->set('admin', $user);
				return $this->redirect('/admin/index');
			}
		}

		return $this->render(array(
			'usId' => $usId,
			'usPs' => $usPs,
			'errors' => $errors,
			'_token' => $this->generateCsrfToken('admin/signin'),
		), 'signin');
	}

	public function signoutAction()
	{
		$this->session->clear();
		$this->session->setAuthenticated(false);

		return $this->redirect('/admin/signin');
	}


	public function calcAction()
	{
		$session = $this->session->get('admin');
		$admin_repository = $this->db_manager->get('Admin');
		$last = $admin_repository->lastCalcTime();
		$lastCalcTime = $last['date'];

		// clkしたユーザーの合計クリック数とnowPt
		$clkUsersStatus = $admin_repository->clkUsersClkSumAndPts($lastCalcTime);

		// 集計1段階 クリックしたユーザーのポイントを分配

		$N = 0;
		// ClkをしたuserNのPtを全クリック数に従い分配,tbsetにinsert
		foreach ($clkUsersStatus as $noUse) {
			$userNo = $clkUsersStatus[$N]['usNo'];
			// ユーザーiがレコード毎にクリックした数を算出
			$sendClksSumToUser = $admin_repository->sendClksSumToUser($lastCalcTime, $userNo);

			$i = 0;
			foreach ($sendClksSumToUser as $noUse) {
				$usNo = $sendClksSumToUser[$i]['usNo'];
				$seUs = $sendClksSumToUser[$i]['seUs'];
				$seClk = $sendClksSumToUser[$i]['seClk'];
				$dTm = $sendClksSumToUser[$i]['dTm'];

				// clkしたユーザーの1クリックあたりのPt計算
				// ユーザーNへのクリック数 / 全ユーザーへのクリック数 ＊ 現在のポイント
				$getPt = $seClk / $clkUsersStatus[$N]['clk_sum'] * $clkUsersStatus[$N]['nowPt'];

				$admin_repository->clkUsersPts_tbsetInsert($usNo, $seUs, $getPt, $dTm);
				$i++;
			}
			$N++;
		}


		// 集計第二段階 nowPtのベーシックインカム的な補正計算
		$adsetting_repository = $this->getAdminSetting();
		$setting = $adsetting_repository->fetchSettingValue();
		$minPt = $setting['userMinPt'];
		$defaultPt = $setting['userDefaultPt'];



		return $this->render(array(
			'body' => '',
			'lastCalcTime' => $lastCalcTime,
			'clkUsersStatus' => $clkUsersStatus,
			'_token' => $this->generateCsrfToken('admin/post'),
		));
	}

}
