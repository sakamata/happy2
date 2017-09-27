<?php

class AccountController extends Controller
{
	// ログインが必要なActionを記述登録
	protected $auth_actions = array('signout','follow','editProfile');

	// account関連のリダイレクト
	// 非ログイン => account/signin , ログイン中 => ホーム画面
	public function indexAction()
	{
		return $this->redirect('/');
	}

	// generateCsrfToken( controller名 / action名 )
	// 単にレンダリングをさせるだけだが フォームの為の_tokenを発行
	//Done
	public function signupAction()
	{
		// $this->redirectAction();
		if ($this->session->isAuthenticated()) {
			return $this->redirect('/');
		}

		return $this->render(array(
			'usName' => '',
			'usId' => '',
			'usPs' => '',
			'facebookLink' => $this->facebookAuthenticateLinkMake('facebooksigncheck'),
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
		} elseif (!$this->db_manager->get('User')->isUniqueUserId($usId)) {
			$errors[] = 'このユーザーIDは既に使用されています。';
		}

		if (!strlen($usPs)) {
			$errors[] = 'パスワードを入力してください';
		} elseif (!preg_match('/\A[a-z\d]{4,30}+\z/i', $usPs)) {
			$errors[] = 'パスワードは4～30文字以内入力してください。';
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
			'facebookLink' => $this->facebookAuthenticateLinkMake('facebooksigncheck'),
			'_token' => $this->generateCsrfToken('account/signup'),
		), 'signup');
	}

	//プロフィール編集画面表示
	public function editProfileAction()
	{
		$user = $this->session->get('user');
		return $this->render(array(
			'user' => $user,
			'facebookLink' => $this->facebookAuthenticateLinkMake('facebookeditprofilecheck'),
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

		// ***ToDo*** パスワード編集の際のバリデート処理

		// 画像チェック処理
		$imageFile = $this->request->getPostFile('imageFile');
		// 画像がセットされていれば画像チェック
		if ($imageFile['tmp_name']) {

			if (!isset($imageFile['error']) || !is_int($imageFile['error'])) {
				$errors[] ='パラメータが不正です';
			}

			if (!$imageFile['name'] || $imageFile['size'] == 0) {
				$errors[] ='この画像ファイルは使用できません';
			}

			if ($imageFile['size'] > 1024*1024*5) {
				$errors[] = 'アップロードできる画像サイズは5MBまでです';
			}

			switch ($imageFile['type']) {
				case 'image/jpeg':
					$extension = '.jpg';
					break;
				case 'image/gif':
					$extension = '.gif';
					break;
				case 'image/png':
					$extension = '.png';
					break;
				default:
					$errors[] = 'アップロードできるのはJPEG,GIF,PNGのみです';
					// imagedestroy($imageFile);
					$extension = '';
					break;
			}

			if ($imageFile['tmp_name'] && count($errors) === 0) {
				// DBに入れる画像Pathを発行
				$usImgPath = $user['usId'] . '.jpg';
				// 画像を一時保存
				$path_name = '../web/user/img/'. $user['usId']. $extension;
				$check = move_uploaded_file($imageFile['tmp_name'], $path_name);
				if(!$check) {
					$errors[] ='この画像ファイルは使用できません';
					unlink($path_name);
				}

				if (count($errors) === 0) {

					// png画像を .jpg に変換
					if ($imageFile['type'] == 'image/png') {
						$img= imagecreatefromstring(file_get_contents($path_name));
						imagejpeg($img,'../web/user/img/'. $user['usId'].'.jpg');
						imagedestroy($img);
						unlink($path_name);
					}

					// gif画像を .jpg に変換
					if ($imageFile['type'] == 'image/gif') {
						$img= imagecreatefromstring(file_get_contents($path_name));
						imagejpeg($img,'../web/user/img/'. $user['usId'].'.jpg');
						imagedestroy($img);
						unlink($path_name);
					}

					// orientationFixedImage( outputImage , inputImage );
					$this->orientationFixedImage($path_name, $user['usId']);
					$largeFile = '../web/user/img/'. $user['usId']. '.jpg';

					// 縦、横、大きい方をトリミング、正方形に
					//元画像の縦横の大きさを比べてどちらかにあわせる
					// なおかつ縦横の差をコピー開始位置として使えるようセット
					list($w, $h) = getimagesize($largeFile);
					if ($w > $h) {
						$diff = ($w - $h) * 0.5;
						$diffW = $h;
						$diffH = $h;
						$diffY = 0;
						$diffX = $diff;
					}elseif($w < $h){
						$diff = ($h - $w) * 0.5;
						$diffW = $w;
						$diffH = $w;
						$diffY = $diff;
						$diffX = 0;
					}elseif($w === $h){
						$diffW = $w;
						$diffH = $h;
						$diffY = 0;
						$diffX = 0;
					}
					//サムネイルサイズ指定、土台の画像を作る
					$thumbW = 200;
					$thumbH = 200;
					$thumbnail = imagecreatetruecolor($thumbW, $thumbH);

					//元画像を読み込む
					$baseImage = imagecreatefromjpeg($largeFile);

					//土台の画像に合わせて元の画像を縮小しコピーペーストする
					imagecopyresampled($thumbnail, $baseImage, 0, 0, $diffX, $diffY, $thumbW, $thumbH, $diffW, $diffH);

					//圧縮率を設定して保存
					imagejpeg($thumbnail, '../web/user/img/' . $user['usId'] . '.jpg', 60);
					sleep(2);
					imagedestroy($thumbnail);
					imagedestroy($baseImage);
				} // error count 0
			}
		// 画像がセットされていない場合
		} else {
			// 画像Pathは従来のまま
			$usImgPath = $user['usImg'];
			$imageFile = '';
		}

		$viewType = $this->request->getPost('viewType');

		// プロフィール編集があった場合はDBに名前と画像Pathを収納
		// エラーが無い、かつ　名前の変更か、画像がセットされているか？
		// かつ　現在のCookieの表示設定値と異なっているか?
		if (count($errors) === 0 && ( $user['usName'] != $usName || $imageFile || $_COOKIE["viewType"] != $viewType)) {
			$this->db_manager->get('User')->profileEdit($usId, $usName, $usImgPath);
			$user = $this->db_manager->get('User')->fetchByUserName($usId);
			$this->session->set('user', $user);

			// 画面表示設定をCookieに保存
			if ($viewType === 'large' || $viewType === 'small') {
				setcookie("viewType", $viewType, time() + 60*60*24*30,'/');
			}
		}
		if (count($errors) === 0) {
			return $this->redirect('/');
		} else {
			$infos = array();
			return $this->render(array(
				'user' => $user,
				'infos' => $infos,
				'errors' => $errors,
				'_token' => $this->generateCsrfToken('account/editProfile'),
				'facebookLink' => $this->facebookAuthenticateLinkMake('facebookeditprofilecheck'),
			), 'editProfile');
		}
	}

	public function facebookEditProfileCheckAction()
	{
		// Facebookより返ったユーザー関連の情報（連想配列）
		$fbUserStatus = $this->getFacebookStatus();

		// FB IDがDBに存在するか確認
		$facebookId = $fbUserStatus['id'];
		$facebookName = $fbUserStatus['name'];
		$res = $this->db_manager->get('User')->facebookIdExistenceCheck($facebookId);
		$errors = array();
		$infos = array();
		if ($res) {	// issetでは駄目！
			// 有る場合は重複を通知
			$errors[] = 'このfacebookアカウントは他のHappyアカウントと既に連携されています。複数のアカウントとの連携はできません。';
		} else {
			// 無い場合はログイン中のuserのカラムとsessionにFBIDをset
			$_SESSION['user']['facebookId'] = $facebookId;
			$user = $this->session->get('user');
			$this->db_manager->get('User')->facebookIdAdd($user['usId'], $facebookId);
			$infos[] = 'facebookアカウントと連携しました。次回ログインよりFacebookアカウントで簡単にログインができます。';
		}

		return $this->render(array(
			'user' => $user,
			'infos' => $infos,
			'errors' => $errors,
			'_token' => $this->generateCsrfToken('account/editProfile'),
			'facebookLink' => $this->facebookAuthenticateLinkMake('facebookeditprofilecheck'),
		), 'editProfile');
	}

	public function fbJoinRemoveCheckAction()
	{
		$user = $this->session->get('user');
		if ($user['usPs']) {
			return $this->redirect('/account/fbremovepasswordform');
		} else {
			return $this->redirect('/account/sethappypasswordform');
		}
	}

	// アカウント連携でHappyのPassword未設定の場合のPassword登録form
	public function SetHappyPasswordFormAction()
	{
		return $this->render(array(
			'usPs' => null,
			'usPs2' => null,
			'_token' => $this->generateCsrfToken('account/sethappypasswordform'),
		));
	}

	public function SetHappyPasswordAuthenticateAction()
	{
		if (!$this->request->isPost()) {
			$this->forward404();
		}

		$token = $this->request->getPost('_token');
		if (!$this->checkCsrfToken('account/sethappypasswordform', $token)) {
			return $this->redirect('/account/sethappypasswordform');
		}

		$usPs = $this->request->getPost('usPs');
		$usPs2 = $this->request->getPost('usPs2');
		$errors = $this->doublePasswordChecker($usPs, $usPs2);

		if (count($errors) === 0) {
			$user = $this->session->get('user');
			$user_repository = $this->db_manager->get('User');
			$user = $user_repository->fetchByUserName($user['usId']);
			$usPs = $user_repository->hashPassword($usPs);
			// Haapy password update
			$this->db_manager->get('User')->passwordChange($user['usId'], $usPs);

			return $this->facebookJoinRemoveAction();
		}

		return $this->render(array(
			'infos' => null,
			'errors' => $errors,
			'_token' => $this->generateCsrfToken('account/sethappypasswordform'),
			'usPs' => $usPs,
			'usPs2' => $usPs2,
		),'sethappypasswordform');
	}

	public function doublePasswordChecker($usPs, $usPs2)
	{
		$errors = array();
		if ($usPs !== $usPs2) {
			$errors[] = '２つのパスワードが一致しません。';
		}

		if (!strlen($usPs)) {
			$errors[] = 'パスワードを入力してください。';
		}

		if (!strlen($usPs2)) {
			$errors[] = 'パスワード（確認）を入力してください。';
		} elseif (!preg_match('/\A[a-z\d]{4,30}+\z/i', $usPs)) {
			$errors[] = 'パスワードは4～30文字以内入力してください。';
		}
		return $errors;
	}

	public function fbRemovePasswordFormAction()
	{
		return $this->render(array(
			'usPs' => null,
			'usPs2' => null,
			'_token' => $this->generateCsrfToken('account/fbremovepasswordform'),
		));
	}

	public function fbRemoveAuthenticateAction()
	{
		if (!$this->request->isPost()) {
			$this->forward404();
		}

		$token = $this->request->getPost('_token');
		if (!$this->checkCsrfToken('account/fbremovepasswordform', $token)) {
			return $this->redirect('/account/fbremovepasswordform');
		}
		$usPs = $this->request->getPost('usPs');
		$usPs2 = $this->request->getPost('usPs2');

		$errors = $this->doublePasswordChecker($usPs, $usPs2);

		if (count($errors) === 0) {
			$user = $this->session->get('user');
			$user_repository = $this->db_manager->get('User');
			$user = $user_repository->fetchByUserName($user['usId']);

			if ($user['usPs'] === $user_repository->hashPassword($usPs)) {
				return $this->facebookJoinRemoveAction();
			}else {
				$errors[] = '設定中のパスワードと一致しません。';
			}
		}

		return $this->render(array(
			'infos' => null,
			'errors' => $errors,
			'_token' => $this->generateCsrfToken('account/fbremovepasswordform'),
			'usPs' => $usPs,
			'usPs2' => $usPs2,
		),'fbremovepasswordform');
	}

	// プロフィール編集画面でのfacebook連携解除処理
	public function facebookJoinRemoveAction()
	{
		$_SESSION['user']['facebookId'] = null;
		$user = $this->session->get('user');
		$this->db_manager->get('User')->facebookIdAdd($user['usId'], $facebookId = null);

		$infos = array();
		$infos[] = 'facebookとの連携を解除しました。次回よりユーザーIDとパスワードでログインしてください。';

		return $this->render(array(
			'user' => $user,
			'infos' => $infos,
			'_token' => $this->generateCsrfToken('account/editProfile'),
			'facebookLink' => $this->facebookAuthenticateLinkMake('facebookeditprofilecheck'),
		), 'editProfile');
	}

	// Thanks! http://www.glic.co.jp/blog/archives/88
	// 画像の左右反転
	public function image_flop($image)
	{
		// 画像の幅を取得
		$w = imagesx($image);
		// 画像の高さを取得
		$h = imagesy($image);
		// 変換後の画像の生成（元の画像と同じサイズ）
		$destImage = @imagecreatetruecolor($w,$h);
		// 逆側から色を取得
		for($i=($w-1);$i>=0;$i--){
			for($j=0;$j<$h;$j++){
				$color_index = imagecolorat($image,$i,$j);
				$colors = imagecolorsforindex($image,$color_index);
				imagesetpixel($destImage,abs($i-$w+1),$j,imagecolorallocate($destImage,$colors["red"],$colors["green"],$colors["blue"]));
			}
		}
		return $destImage;
	}

	// 上下反転
	public function image_flip($image)
	{
		// 画像の幅を取得
		$w = imagesx($image);
		// 画像の高さを取得
		$h = imagesy($image);
		// 変換後の画像の生成（元の画像と同じサイズ）
		$destImage = @imagecreatetruecolor($w,$h);
		// 逆側から色を取得
		for($i=0;$i<$w;$i++){
			for($j=($h-1);$j>=0;$j--){
				$color_index = imagecolorat($image,$i,$j);
				$colors = imagecolorsforindex($image,$color_index);
				imagesetpixel($destImage,$i,abs($j-$h+1),imagecolorallocate($destImage,$colors["red"],$colors["green"],$colors["blue"]));
			}
		}
		return $destImage;
	}

	// 画像を回転
	public function image_rotate($image, $angle, $bgd_color)
	{
		$image = imagerotate($image, $angle, $bgd_color, 0);
		return $image;
	}

	// 画像の方向を正す
	public function orientationFixedImage($input, $userID){
		$image = ImageCreateFromJPEG($input);
		$exif_datas = @exif_read_data($input);
		if(isset($exif_datas['Orientation'])){
			$orientation = $exif_datas['Orientation'];
			if($image){
				// 未定義
				if($orientation == 0){
				// 通常
				}else if($orientation == 1){
				// 左右反転
				}else if($orientation == 2){
					$image = $this->image_flop($image);
				// 180°回転
				}else if($orientation == 3){
					$image = $this->image_rotate($image,180, 0);
				// 上下反転
				}else if($orientation == 4){
					$image = $this->image_Flip($image);
				// 反時計回りに90°回転 上下反転
				}else if($orientation == 5){
					$image = $this->image_rotate($image,90, 0);
					$image = $this->image_flip($image);
				// 時計回りに90°回転 90 -> 270
				}else if($orientation == 6){
					$image = $this->image_rotate($image,270, 0);
				// 時計回りに90°回転 上下反転
				}else if($orientation == 7){
					$image = $this->image_rotate($image,270, 0);
					$image = $this->image_flip($image);
				// 反時計回りに90°回転 270 -> 90
				}else if($orientation == 8){
					$image = $this->image_rotate($image,90, 0);
				}
			}
		}
		// 画像の書き出し
		ImageJPEG($image ,'../web/user/img/'. $userID. '.jpg');
		return false;
	}

	//Done
	public function signinAction()
	{
		if ($this->session->isAuthenticated()) {
			return $this->redirect('/');
		}
		// facebook PHP SDK用のセッション開始
		session_start();

		return $this->render(array(
			'usId' => '',
			'usPs' => '',
			'_token' => $this->generateCsrfToken('account/signin'),
			'facebookLink' => $this->facebookAuthenticateLinkMake('facebooksigncheck'),
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
			'facebookLink' => $this->facebookAuthenticateLinkMake('facebooksigncheck'),
			'_token' => $this->generateCsrfToken('account/signin'),
		), 'signin');
	}

	// Facabookログインの為の遷移先URLを生成する
	public function facebookAuthenticateLinkMake($action)
	{
		$chk = file_exists("../php-graph-sdk-5.x/src/Facebook/autoload.php");
		if (!$chk){
			require_once '/var/www/html/happy2/php-graph-sdk-5.x/src/Facebook/autoload.php';						
		} else {
			require_once '../php-graph-sdk-5.x/src/Facebook/autoload.php';
		}
		
		$path = dirname(__FILE__) . '/../../../hidden/info.php';
		require $path;

		$fb = new Facebook\Facebook([
			'app_id' => $FacebookAppId,
			'app_secret' => $FacebookAppSecret,
			'default_graph_version' => 'v2.8'
		]);

		$helper = $fb->getRedirectLoginHelper();
		$scope = ['public_profile'];
		$link = 'https://' . $permitDomain . '/happy2/web/account/'. $action;
		$link = $helper->getLoginUrl($link, $scope);
		return $link;
	}

	// 最低限処理済み (FB PHP SDKから id name のみ取得)
	// ***ToDo*** 全角名前とか画像等の取得
	public function getFacebookStatus()
	{
		require_once '../php-graph-sdk-5.x/src/Facebook/autoload.php';
		$path = dirname(__FILE__) . '/../../../hidden/info.php';
		require $path;

		$fb = new Facebook\Facebook([
			'app_id' => $FacebookAppId,
			'app_secret' => $FacebookAppSecret,
			'default_graph_version' => 'v2.8'
		]);

		$helper = $fb->getRedirectLoginHelper();
		try {
			$access_token = $helper->getAccessToken();
			$res = $fb->get( '/me', $access_token);

		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			echo $e->getMessage();
			exit();

		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			echo $e->getMessage();
			exit();
		}

		// Facebookより返ったユーザー関連の情報（連想配列 id,name）
		$fbUserStatus = $res->getDecodedBody();
		return $fbUserStatus;
	}


	// Facabookログインのリダイレクト先の処理 FBIDのDBとの突合せでログイン or form表示
	public function facebookSignCheckAction()
	{
		// Facebookより返ったユーザー関連の情報（連想配列）
		$fbUserStatus = $this->getFacebookStatus();

		// FB IDがDBに存在するか確認
		$facebookId = $fbUserStatus['id'];
		$facebookName = $fbUserStatus['name'];
		$res = $this->db_manager->get('User')->facebookIdExistenceCheck($facebookId);
		if ($res) {	// issetでは駄目！
			// 有る場合はログイン
			$usId = $res['usId'];
			$user_repository = $this->db_manager->get('User');
			$user = $user_repository->fetchByUserName($usId);
			$this->session->setAuthenticated(true);
			$this->session->set('user', $user);
			return $this->redirect('/');
		}

		// 無い場合はセッションにFBID入れてリダイレクト
		$this->session->set('facebookId', $facebookId);
		$this->session->set('facebookName', $facebookName);
		return $this->redirect('/account/facebookjoinform');

		// デバッグ用、本来は不要
		// return $this->render(array(
		// 	'fbUserStatus' => $fbUserStatus,
		// ));
	}

	// FB連携 入力フォーム画面 HappyIDの存在をユーザーに確認
	public function facebookJoinFormAction()
	{
		if (!$this->session->get('facebookId')) {
			// 処理を中止
			$this->signoutAction();
			return $this->redirect('/');
		}

		$currentUsId = $this->session->get('facebookName');
		// HappyID の生成 $currentUsId をhappy のusID準拠のものに変更
		$usIdSignup = $this->generateHappyId($currentUsId);

		return $this->render(array(
			'currentUsId' => $currentUsId,
			'usIdSignup' => $usIdSignup,
			'usIdJoin' => '',
			'usPs' => '',
			'errorsSiginup' => null,
			'errorsJoin' => null,
			'_token' => $this->generateCsrfToken('account/facebookjoinform'),
		));
	}

	// FB連携　アカウント新規登録チェック （HappyID持っていない）
	public function facebookJoinRegisterAction()
	{
		if (!$this->request->isPost()) {
			$this->forward404();
		}

		$token = $this->request->getPost('_token');
		if (!$this->checkCsrfToken('account/facebookjoinform', $token)) {
			return $this->redirect('/account/facebookjoinform');
		}

		$currentUsId = $this->session->get('facebookName');
		$usId = $this->request->getPost('usIdSignup');
		$usPs = null;
		$usName = $this->session->get('facebookName');
		$facebookId = $this->session->get('facebookId');

		// $usName を16文字以内に置換
		if (strlen($usName) > 16) {
			$usName = substr($usName, 0, 16);
		}

		$errors = array();

		if (!strlen($usId)) {
			$errors[] = 'ユーザーIDを入力してください';
		} else if (!preg_match('/^\w{3,20}$/', $usId)) {
			$errors[] = 'ユーザーIDは半角英数字及びアンダースコアを3～20文字以内で入力してください。';
		} elseif (!$this->db_manager->get('User')->isUniqueUserId($usId)) {
			$errors[] = 'このユーザーIDは既に使用されています。';
		}
		// ***ToDo*** error追加 このFacebookアカウントは既に連携済みです。


		// アカウント発行と初期処理、ログイン処理
		if (count($errors) === 0) {
			// FBID insetの際ユニーク確認をする
			$this->db_manager->get('User')->insert($usId, $usPs, $usName, $facebookId);
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
			'currentUsId' => $currentUsId,
			'usIdSignup' => $usId,
			'usIdJoin' => '',
			'usPs' => $usPs,
			'errorsSiginup' => $errors,
			'errorsJoin' => null,
			'_token' => $this->generateCsrfToken('account/facebookjoinform'),
		), 'facebookjoinform');
	}

	// HappyID の自動作成
	// $currentUsId を正規表現,20文字以内に置換　記号は　_ のみ どれも駄目なら空欄
	public function generateHappyId($currentUsId)
	{
		// 全角文字を削除
		$currentUsId = preg_replace('/[^\x01-\x7E]/u' , '' , $currentUsId);
		// 半角記号を _ に置換
		$usId = preg_replace('/\W/u' , '_' , $currentUsId);
		// 20文字以内に加工
		if (strlen($usId) > 20) {
			$usId = substr($usId, 0, 20);
		}
		// DBへ重複確認
		if (!$this->db_manager->get('User')->isUniqueUserId($usId)) {
			// 重複ありなら文末数値付与、文字数オーバーならID末尾削る
			if (strlen($usId) > 17) {
				$usId = substr($usId, 0, 17);
			}
			$num = rand(1, 999);
			$usId = $usId . $num;
			// 生成した$suIdは確率論的に完璧にユニークにならない。
			// しかし生成したIDが非ユニークなら、あとはユーザーの入力修正に任せる
		}
		return $usId;
	}

	// FB連携　既存アカウント連携チェック、ログイン処理（HappyID持っている）
	public function facebookJoinSigninAction()
	{
		if (!$this->request->isPost()) {
			$this->forward404();
		}

		$token = $this->request->getPost('_token');
		if (!$this->checkCsrfToken('account/facebookjoinform', $token)) {
			return $this->redirect('/account/facebookjoinform');
		}

		// 既存ログイン処理
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
				// FBIDをDBに追加
				$facebookId = $this->session->get('facebookId');
				$this->db_manager->get('User')->facebookIdAdd($usId, $facebookId);
				// ログイン処理
				$this->session->setAuthenticated(true);
				$this->session->set('user', $user);
				$_SESSION['user']['facebookId'] = $facebookId;
				return $this->redirect('/');
			}
		}

		return $this->render(array(
			'currentUsId' => $currentUsId,
			'usIdSignup' => '',
			'usIdJoin' => $usId,
			'usPs' => $usPs,
			'errorsSiginup' => null,
			'errorsJoin' => $errors,
			'_token' => $this->generateCsrfToken('account/facebookjoinform'),
		), 'facebookjoinform');
	}

	//Done
	public function signoutAction()
	{
		$this->session->clear();
		$this->session->setAuthenticated(false);

		return $this->redirect('/account/signin');
	}

}
