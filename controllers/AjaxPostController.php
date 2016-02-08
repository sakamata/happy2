<?php
class AjaxPostController extends Controller
{

	// ログインが必要なActionを記述登録
	protected $auth_actions = array(
		'clickPost',
		'follow'
	);

	public function clickPostAction()
	{
		$token = strval($this->request->getPost('click_token'));
		if (!$this->checkCsrfTokenLasting('ajaxPost/clickPost', $token)) {
			error_log('Error!! click_token check!!');
			return;
		}

		$user = $this->session->get('user');
		$usNo = intval($user['usNo']);
		$postDateTime = strval($this->request->getPost('postDateTime'));
		$clicks = $this->request->getPost('clicks');
		// error_log('エラーログ！！！');
		// error_log(print_r($clicks,true),0);

		$this->db_manager->get('User')->clickPost($usNo, $clicks);
	}

	// POST時間の修正
	// POSTされるobjに "postDateTime" が投稿時間として送られてくる
	// ユーザー側のTime stamp をサーバー側の時間軸基準に修正
	// 受けた最後のTime stamp を現在時間として受け取り,それを基準に差分で時間調整
	// POSTのintavalより大きな値（過去や未来）の時間があった場合も、inteaval内の時間に変換してDB収納
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
