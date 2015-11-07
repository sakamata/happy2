<?php



class StatusController extends Controller
{

	protected $auth_actions = array('index', 'post');

	public function indexAction()
	{
		$user = $this->session->get('user');
		$statuses = $this->db_manager->get('Status')->fetchUserStatus($user['usNo']);

		return $this->render(array(
			'statuses' => $statuses,
			'body' => '',
			'_token' => $this->generateCsrfToken('status/post'),
		));
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

	public function postAction()
	{
		if (!$this->request->isPost()) {
			$this->forward404();
		}

		$token = $this->request->getPost('_token');
		if (!$this->checkCsrfToken('status/post', $token)) {
			return $this->redirect('/');
		}

		$body = $this->request->getPost('body');

		$errors = array();

		if (!strlen($body)) {
			$errors[] = '一言を入力してください。';
		} elseif (mb_strlen($body) > 200) {
			$errors[] = '200文字以内で入力してください。';
		}

		if (count($errors) === 0) {
			$user = $this->session->get('user');
			$this->db_manager->get('Status')->insert($user['usNo'], $body);

			return $this->redirect('/');
		}

		$user = $this->session->get('user');
		$statuses = $this->db_manager->get('Status')->fetchAllPersonalArchivesByUserId($user['usNo']);

		return $this->render(array(
			'errors' => $errors,
			'body' => $body,
			'statuses' => $statuses,
			'_token' => $this->generateCsrfToken('status/post'),
		),'index');

	}
}
