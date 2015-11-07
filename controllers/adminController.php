<?php
class AdminController extends Controller
{
	public function indexAction()
	{
		$session = $this->session->get('admin');

		$admin_repository = $this->db_manager->get('Admim');
		$users = $admin_repository->fetchAlltbus($limit);


		return $this->render(array(
			'body' => '',
			'users' => '',
			'_token' => $this->generateCsrfToken('admin/post'),
		));
	}

	public function signinAction()
	{
		if ($this->session->isAuthenticated()) {
			return $this->redirect('/account');
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

}
