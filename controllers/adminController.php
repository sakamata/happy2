<?php
class AdminController extends Controller
{
	public $tableNames = array('tbus', 'tbgvn', 'tbset', 'tbfollow', 'tbcalctime');
	public $commands = array('Reset', 'DummyIn');

	public function indexAction()
	{
		$session = $this->session->get('admin');
		$admin_repository = $this->db_manager->get('Admin');
		$adsetting_repository = $this->getAdminSetting();

		$setting = $adsetting_repository->fetchSettingValue();
		$limit = $setting['adminTablesViewLimit'];

		// ***ToDo***ページ送り制御でlimit数で増減させる
		$offset = 0;
		$tables = [];
		$tbCounts = [];
		$key = [];
		foreach ($this->tableNames as $tableName) {
			if ($tableName == 'tbus') {
				// PassWord非表示の為の別処理
				$tables[$tableName] = $admin_repository->fetchAlltbus($limit, $offset);
			} else {
				$tables[$tableName] = $admin_repository->fetchAllTable($tableName, $limit, $offset);
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

		// AdminRepossitoryのメソッド名を生成
		$RepossitoryCommnd = $tableName.$command;
		if (!method_exists('AdminRepository', $RepossitoryCommnd)) {
			$this->forward404();
		}
		$this->db_manager->get('Admin')->$RepossitoryCommnd($tableName);
		return $this->redirect('/admin/index');
	}

	public function getAdminSetting()
	{
		$adsetting_repository = $this->db_manager->get('AdminSetting');
		return $adsetting_repository;
	}

	// ***ToDo***
	public function pagerAction($table, $page, $offset)
	{
		return $page;
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

}
