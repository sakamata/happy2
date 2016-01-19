<?php
class AjaxPostController extends Controller
{

	// ログインが必要なActionを記述登録
	protected $auth_actions = array(
		'sendHappy',
		'follow'
	);

	public function sendHappyAction()
	{
		$token = strval($this->request->getPost('f_token'));
		if (!$this->checkCsrfToken('ajaxPost/sendHappy', $token)) {
			return;
		}
		$sendUser = intval($this->request->getPost('sendUser'));
		$clickCount = intval($this->request->getPost('clickCount'));
		$user = $this->session->get('user');
		$usNo = intval($user['usNo']);
	}

	// POST時間の修正
	// ユーザー側のTime stamp をサーバー側の時間軸基準に修正
	// 受けた最後のTime stamp を現在時間として受け取りそれを基準に時間調整
	// POSTされる値の最後に sendUser=0 clickCount=0 がPostTimeのトリガーとして送られてくる
	public function postTimeAdjustmentAction()
	{
		$data = 'This is server time!! get XHR.getResponseHeader("Date") ';
		echo $data;
	}

	public function setTime()
	{
		# code...
	}

	public function followAction()
	{
		$token = strval($this->request->getPost('f_token'));
		if (!$this->checkCsrfToken('ajaxPost/follow', $token)) {
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
