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
			'usId' => '',
			'usPs' => '',
			'_token' => $this->generateCsrfToken('admin/signin'),
		));
	}


	public function admin_signinAction()
	{
		if ($this->session->isAuthenticated()) {
			return $this->redirect('admin/signin');
		}
		// generateCsrfToken(' コントローラ名 / アクション名 ')
		return $this->render(array(
			'usId' => '',
			'usPs' => '',
			'_token' => $this->generateCsrfToken('admin/admin_signin'),
		));

	}


	public function authenticateAction()
	{
		if ($this->session->isAuthenticated()) {
			return $this->redirect('admin/signin');
		}

		if (!$this->request->isPost()) {
			$this->forward404();
		}

		$token = $this->request->getPost('_token');
		if (!$this->checkCsrfToken('admin/signin', $token)) {
			return $this->redirect('/admin/signin');
		}

		$usId = $this->request->getPost('usId');
		$password = $this->request->getPost('usPs');

		$errors = array();

		if (!strlen($usId)) {
			$errors[] = 'ユーザーIDを入力してください';
		}

		if (!strlen($password)) {
			$errors[] = 'パスワードを入力してください';
		}

		if (count($errors) === 0) {

			$user_repository = $this->db_manager->get('usId');
			$user = $user_repository->fetchByUserName($usId);

			if (!$user || $user['usPs'] !== $user_repository->hashPassword($password)) {
				$errors[] = 'ユーザーIDかパスワードが正しくありません。';
			} else {
				$this->session->setAuthenticated(true);
				$this->session->set('usId', $user);
				return $this->redirect('admin/signin');
			}
		}

		return $this->render(array(
			'usId' => $usId,
			'usPs' => $password,
			'errors' => $errors,
			'_token' => $this->generateCsrfToken('admin/signin'),
		), 'admin_signin');
	}

	public function signoutAction()
	{
		$this->session->clear();
		$this->session->setAuthenticated(false);

		return $this->redirect('/account/signin');
	}

}
