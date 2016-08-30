<?php

class AccountController extends Controller
{
	// ログインが必要なActionを記述登録
	protected $auth_actions = array('index', 'signout','follow','editProfile');

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

			// tb_user_statusに位置と現在日時を登録
			// ***ToDo***user側で緯度経度取得の実装
			$latitude = $this->request->getPost('latitude');
			$longitude = $this->request->getPost('longitude');
			$this->db_manager->get('User')->tb_user_statusRegisterInsert($usNo, $latitude, $longitude);

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


	public function editProfileAction()
	{
		$user = $this->session->get('user');

		return $this->render(array(
			'user' => $user,
			'_token' => $this->generateCsrfToken('account/editProfile'),
		));
	}

	//プロフィール編集チェック
	public function profileConfirmAction()
	{
		if (!$this->request->isPost()) {
			$this->forward404();
		}

		$token = $this->request->getPost('_token');
		if (!$this->checkCsrfToken('account/editProfile', $token)) {
			return $this->redirect('/account/editProfile');
		}

		$user = $this->session->get('user');
		$usId = $user['usId'];
		if(!$usId){
			$this->signout();
		}

		$errors = array();
		$usName = $this->request->getPost('usName');
		if (!mb_strlen($usName)) {
			$errors[] = '名前を入力してください';
		} elseif (2 > mb_strlen($usName) || mb_strlen($usName) > 16) {
			$errors[] = '名前は2～16文字以内で入力してください。';
		}
/*
		if (!strlen($usPs)) {
			$errors[] = 'パスワードを入力してください';
		} elseif (4 > strlen($usPs) || strlen($usPs) > 30) {
			$errors[] = 'パスワードは4～30文字以内で入力してください。';
		}
*/
		// 画像チェック処理
		$imageFile = $this->request->getPostFile('imageFile');
		if (!isset($imageFile['error']) || !is_int($imageFile['error'])) {
			$errors[] ='パラメータが不正です';
		}

		if (!$imageFile['name'] || $imageFile['size'] == 0) {
			$usImg = $user['usImg'];
		} else {
			if ($imageFile['size'] > 1024*1024*5) {
				$errors[] = 'アップロードできる画像サイズは5Mまでです';
				$usImg = $user['usImg'];
			}
			switch ($imageFile['type']) {
				case 'image/jpeg':
					$extension = '.jpg';
					$usImg = $user['usId'] . $extension;
					break;
				case 'image/gif':
					$extension = '.gif';
					$usImg = $user['usId'] . $extension;
					break;
				case 'image/png':
					$extension = '.png';
					$usImg = $user['usId'] . $extension;
					break;
				default:
					$errors[] = 'アップロードできるのはJPEG,GIF,PNGのみです';
					$usImg = $user['usImg'];
					break;
			}
		}

		if (count($errors) === 0) {
			$this->db_manager->get('User')->profileEdit($usId, $usName, $usImg);

			// ToDo リサイズ処理 最大100pxに
			// 縦、横、大きい方をトリミング、正方形に
			// リサイズ、100*100に

			if ($imageFile['tmp_name']) {
				move_uploaded_file($imageFile['tmp_name'], '../web/user/img/'. $user['usId']. $extension);
			}

			$user = $this->db_manager->get('User')->fetchByUserName($usId);
			$this->session->set('user', $user);
			return $this->redirect('/');
		}

		return $this->render(array(
			'user' => $user,
			'usImg' => $usImg,
			'imageFile' => $imageFile,
			'errors' => $errors,
			'_token' => $this->generateCsrfToken('account/editProfile'),
		), 'editProfile');
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
