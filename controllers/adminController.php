<?php
// 製作中
class AdminController extends Controller
{
	// ログインが必要なActionを記述登録
	// ///ToDo/// calc table アクションの実装
	protected $auth_actions = array('admin', 'calc','table');

	public function signupAction()
	{
		return $this->render(array(
			'user_name' => '',
			'password' => '',
			'_token' => $this->generateCsrfToken('account/signup'),
		));
	}


	public function indexAction()
	{
		$user = $this->session->get('user');
		$followings = $this->db_manager->get('User')->fetchAllFollowingsByUserId($user['id']);

		return $this->render(array(
			'user' => $user,
			'followings' => $followings,
		));
	}

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
