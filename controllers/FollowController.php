<?php
class FollowController extends Controller
{

	// ログインが必要なActionを記述登録
	protected $auth_actions = array(
		'follow'
	);

	public function followAction()
	{
		$token = strval($this->request->getPost('f_token'));
		if (!$this->checkCsrfToken('follow/follow', $token)) {
			return;
		}
		$followingNo = intval($this->request->getPost('followingNo'));
		$user = $this->session->get('user');
		$usNo = intval($user['usNo']);
		$exist = $this->db_manager->get('Follow')->CheckFollowing($usNo, $followingNo);
		$action = strval($this->request->getPost('followAction'));

		if ($action === 'doFollow'  &&  $exist === false  &&  $usNo !== $followingNo) {
			$this->db_manager->get('Follow')->following($usNo, $followingNo);
		}

		if ($action === 'unFollow'  &&  $exist === true  &&  $usNo !== $followingNo) {
			$this->db_manager->get('Follow')->unFollow($usNo, $followingNo);
		}
	}

}
