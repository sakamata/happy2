<?php
class FollowController extends Controller
{

	// ログインが必要なActionを記述登録
	protected $auth_actions = array(
		'follow'
	);

	public function followAction()
	{
		$token = strval($this->request->getPost('follow_token'));
		// if (!$this->checkCsrfToken('follow/follow', $token)) {
		// 	$this->forward404();
		// }
		// var_dump($token);
		$followingNo = intval($this->request->getPost('followingNo'));
		var_dump($followingNo);
		$user = $this->session->get('user');
		$usNo = intval($user['usNo']);

		var_dump($usNo);
		$exist = $this->db_manager->get('Follow')->CheckFollowing($usNo, $followingNo);
		var_dump($exist);

		$action = strval($this->request->getPost('followAction'));

		if ($action == '1'  &&  $exist === false  &&  $usNo !== $followingNo) {
			$this->db_manager->get('Follow')->following($usNo, $followingNo);
		}

		if ($action == '0'  &&  $exist === true  &&  $usNo !== $followingNo) {
			$this->db_manager->get('Follow')->unFollow($usNo, $followingNo);
		}

		// JS主体の場合は不要
		return $this->redirect('/');
	}

}
