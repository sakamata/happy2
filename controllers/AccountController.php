<?php

class AccountController extends Controller
{
	// ログインが必要なActionを記述登録
	protected $auth_actions = array('index', 'signout','follow');

	// generateCsrfToken( controller名 / action名 )
	// 単にレンダリングをさせるだけだが フォームの為の_tokenを発行
	//Done
	public function signupAction()
	{
		return $this->render(array(
			'usName' => '',
			'usId' => '',
			'usPs' => '',
			'_token' => $this->generateCsrfToken('account/signup'),
		));
	}

	//Done
	// ユーザーアカウント登録とチェック
	public function registerAction()
	{
		if (!$this->request->isPost()) {
			$this->forward404();
		}

		$token = $this->request->getPost('_token');
		if (!$this->checkCsrfToken('account/signup', $token)) {
			return $this->redirect('/account/signup');
		}

		$usName = $this->request->getPost('usName');
		$usId = $this->request->getPost('usId');
		$usPs = $this->request->getPost('usPs');

		$errors = array();

		if (!mb_strlen($usName)) {
			$errors[] = '名前を入力してください';
		} elseif (2 > mb_strlen($usName) || mb_strlen($usName) > 16) {
			$errors[] = '名前は2～16文字以内で入力してください。';
		}

		if (!strlen($usId)) {
			$errors[] = 'ユーザーIDを入力してください';
		} else if (!preg_match('/^\w{3,20}$/', $usId)) {
			$errors[] = 'ユーザーIDは半角英数字及びアンダースコアを3～20文字以内で入力してください。';
		} elseif (!$this->db_manager->get('User')->isUniqueUserName($usId)) {
			$errors[] = 'このユーザーIDは既に使用されています。';
		}

		if (!strlen($usPs)) {
			$errors[] = 'パスワードを入力してください';
		} elseif (4 > strlen($usPs) || strlen($usPs) > 30) {
			$errors[] = 'パスワードは4～30文字以内で入力してください。';
		}

		if (count($errors) === 0) {
			$this->db_manager->get('User')->insert($usId, $usPs, $usName);
			// 自分に1クリックさせる
			$n = $this->db_manager->get('User')->getUserNo($usId);
			$usNo = $n['usNo'];
			$this->db_manager->get('User')->selfOneClick($usNo);

			$this->session->setAuthenticated(true);
			$user = $this->db_manager->get('User')->fetchByUserName($usId);
			$this->session->set('user', $user);

			return $this->redirect('/');
		}

		return $this->render(array(
			'usName' => $usName,
			'usId' => $usId,
			'usPs' => $usPs,
			'errors' => $errors,
			'_token' => $this->generateCsrfToken('account/signup'),
		), 'signup');
	}

/*
	public function indexAction()
	{
		$user = $this->session->get('user');
		$followings = $this->db_manager->get('User')->fetchAllFollowingsByUserId($user['usNo']);

		return $this->render(array(
			'user' => $user,
			'followings' => $followings,
		));
	}
*/

	//Done
	public function signinAction()
	{
		if ($this->session->isAuthenticated()) {
			return $this->redirect('/account');
		}

		return $this->render(array(
			'usId' => '',
			'usPs' => '',
			'_token' => $this->generateCsrfToken('account/signin'),
		));
	}

	public function followAction()
	{
		if (!$this->request->isPost()) {
			$this->forward404();
		}

		$following_name = $this->request->getPost('following_name');
		if (!$following_name) {
			$this->forward404();
		}

		$token = $this->request->getPost('_token');
		if (!$this->checkCsrfToken('account/follow', $token)) {
			return $this->redirect('/user/' . $following_name);
		}

		$follow_user = $this->db_manager->get('User')->fetchByUserName($following_name);
		if (!$follow_user) {
			$this->forward404();
		}

		$user = $this->session->get('user');

		$following_repository = $this->db_manager->get('Following');
		if ($user['usNo'] !== $follow_user['usNo'] && !$following_repository->isFollowing($user['usNo'], $follow_user['usNo'])) {
			$following_repository->insert($user['usNo'], $follow_user['usNo']);
		}

		return $this->redirect('/account');
	}

	//Done
	public function authenticateAction()
	{
		if ($this->session->isAuthenticated()) {
			return $this->redirect('/account');
		}

		if (!$this->request->isPost()) {
			$this->forward404();
		}

		$token = $this->request->getPost('_token');
		if (!$this->checkCsrfToken('account/signin', $token)) {
			return $this->redirect('/account/signin');
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

			$user_repository = $this->db_manager->get('User');
			$user = $user_repository->fetchByUserName($usId);

			if (!$user || $user['usPs'] !== $user_repository->hashPassword($usPs)) {
				$errors[] = 'ユーザーIDかパスワードが正しくありません。';
			} else {
				$this->session->setAuthenticated(true);
				$this->session->set('user', $user);
				return $this->redirect('/');
			}
		}

		return $this->render(array(
			'usId' => $usId,
			'usPs' => $usPs,
			'errors' => $errors,
			'_token' => $this->generateCsrfToken('account/signin'),
		), 'signin');
	}

	//Done
	public function signoutAction()
	{
		$this->session->clear();
		$this->session->setAuthenticated(false);

		return $this->redirect('/account/signin');
	}

}
