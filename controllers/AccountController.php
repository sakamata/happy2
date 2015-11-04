<?php

class AccountController extends Controller
{
	// ログインが必要なActionを記述登録
	protected $auth_actions = array('index', 'signout','follow');

	// generateCsrfToken( controller名 / action名 )
	// 単にレンダリングをさせるだけだが フォームの為の_tokenを発行
	public function signupAction()
	{
		return $this->render(array(
			'user_name' => '',
			'user_id' => '',
			'password' => '',
			'_token' => $this->generateCsrfToken('account/signup'),
		));
	}
	// ***ToDo*** まだ動かず、完成させる
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

		$user_name = $this->request->getPost('user_name');
		$user_id = $this->request->getPost('user_id');
		$password = $this->request->getPost('password');

		$errors = array();

		if (!mb_strlen($user_name)) {
			$errors[] = '名前を入力してください';
		} elseif (2 > mb_strlen($user_name) || mb_strlen($user_name) > 16) {
			$errors[] = '名前は2～16文字以内で入力してください。';
		}

		if (!strlen($user_id)) {
			$errors[] = 'ユーザーIDを入力してください';
		} else if (!preg_match('/^\w{3,20}$/', $user_id)) {
			$errors[] = 'ユーザーIDは半角英数字及びアンダースコアを3～20文字以内で入力してください。';
		} elseif (!$this->db_manager->get('User')->isUniqueUserName($user_id)) {
			$errors[] = 'このユーザーIDは既に使用されています。';
		}

		if (!strlen($password)) {
			$errors[] = 'パスワードを入力してください';
		} elseif (4 > strlen($password) || strlen($password) > 30) {
			$errors[] = 'パスワードは4～30文字以内で入力してください。';
		}

		if (count($errors) === 0) {
			$this->db_manager->get('User')->insert($user_id, $password, $user_name);
			$this->session->setAuthenticated(true);

			$user = $this->db_manager->get('User')->fetchByUserName($user_id);
			$this->session->set('user', $user);

			return $this->redirect('/');
		}

		return $this->render(array(
			'user_name' => $user_name,
			'user_id' => $user_id,
			'password' => $password,
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
	public function signinAction()
	{
		if ($this->session->isAuthenticated()) {
			return $this->redirect('/account');
		}

		return $this->render(array(
			'user_name' => '',
			'password' => '',
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

		$user_name = $this->request->getPost('user_name');
		$password = $this->request->getPost('password');

		$errors = array();

		if (!strlen($user_name)) {
			$errors[] = 'ユーザーIDを入力してください';
		}

		if (!strlen($password)) {
			$errors[] = 'パスワードを入力してください';
		}

		if (count($errors) === 0) {

			$user_repository = $this->db_manager->get('user');
			$user = $user_repository->fetchByUserName($user_name);

			if (!$user || $user['password'] !== $user_repository->hashPassword($password)) {
				$errors[] = 'ユーザーIDかパスワードが正しくありません。';
			} else {
				$this->session->setAuthenticated(true);
				$this->session->set('user', $user);
				return $this->redirect('/');
			}
		}

		return $this->render(array(
			'user_name' => $user_name,
			'password' => $password,
			'errors' => $errors,
			'_token' => $this->generateCsrfToken('account/signin'),
		), 'signin');
	}

	public function signoutAction()
	{
		$this->session->clear();
		$this->session->setAuthenticated(false);

		return $this->redirect('/account/signin');
	}

}
