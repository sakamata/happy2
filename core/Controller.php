<?php
abstract class Controller
{
	protected $controller_name;
	protected $action_name;
	protected $application;
	protected $request;
	protected $response;
	protected $session;
	protected $db_manager;
	protected $auth_actions = array();

	public $lastCalcTime;	// 最終集計時間
	public $allUserCount;	// 全ユーザー数
	public $userViewLimit; // ユーザー表示数
	public $postSecond; // クリック情報の定期POST秒
	public $calcCount; // 集計回数
	protected $myUser; // tbusステータス

	// サービス全体で必要な情報を生成
	public function serviceStatus()
	{
		$allUserCount = $this->db_manager->get('Admin')->tableCount('tbus');
		$this->allUserCount = $allUserCount['tbus'];
		$adsetting = $this->db_manager->get('AdminSetting')->fetchSettingValue();
		$limit = $adsetting['userViewLimitClients'];
		$this->userViewLimit = intval($limit);
		$postSecond = $adsetting['userClickPostIntervalSecond'];
		$this->postSecond = intval($postSecond);

		$calcStatus = $this->db_manager->get('Status')->calcStatus();
		$this->calcCount = $calcStatus['calcCount'];
		$this->lastCalcTime = $calcStatus['lastCalcTime'];
	}


	public function __construct($application)
	{
		$this->controller_name = strtolower(substr(get_class($this), 0, -10));

		$this->application = $application;
		$this->request = $application->getRequest();
		$this->response = $application->getResponse();
		$this->session = $application->getSession();
		$this->db_manager = $application->getDbManager();
	}

	public function run($action, $params = array())
	{
		$this->action_name = $action;

		$action_method = $action . 'Action';
		if (!method_exists($this, $action_method)) {
			$this->forward404();
		}

		if ($this->needsAuthentication($action) && !$this->session->isAuthenticated()) {
			throw new UnauthorizedActionException();
		}

		$content = $this->$action_method($params);

		return $content;
	}

	// ログイン中に必要なActionか確認
	protected function needsAuthentication($action)
	{
		if ($this->auth_actions === true || (is_array($this->auth_actions) && in_array($action, $this->auth_actions))
			) {
			return true;
		}

		return false;
	}

	protected function render($variables = array(), $template = null, $layout = 'layout')
	{
		$defaults = array(
			'request' => $this->request,
			'base_url' => $this->request->getBaseUrl(),
			'href_base' => $this->request->getHrefBase(),
			'req_base' => $this->request->getRequestBase(),
			'session' => $this->session,
		);

		$view = new View($this->application->getViewDir(), $defaults);

		if (is_null($template)) {
			$template = $this->action_name;
		}

		$path = $this->controller_name . '/' .$template;

		return $view->render($path, $variables, $layout);
	}

	protected function forward404()
	{
		throw new HttpNotFoundException('404エラーForwarded 404 page from → ' . $this->controller_name . '/' . $this->action_name);
	}

	protected function redirect($url)
	{
		if (!preg_match('#https?://#', $url)) {
			$protocol = $this->request->isSsl() ? 'https://' : 'http://';
			$host = $this->request->getHost();
			$base_url = $this->request->getBaseUrl();

			$url = $protocol . $host . $base_url . $url;
		}

		$this->response->setStatusCode(302, 'Found');
		$this->response->setHttpHeader('Location', $url);

	}

	protected function generateCsrfToken($form_name)
	{
		$key = 'csrf_tokens/' . $form_name;
		$tokens = $this->session->get($key, array());
		if (count($tokens) >= 10) {
			array_shift($tokens);
		}

		$token = sha1($form_name . session_id() . microtime());
		$tokens[] = $token;

		$this->session->set($key, $tokens);

		return $token;
	}

	protected function checkCsrfToken($form_name, $token)
	{
		$key = 'csrf_tokens/' . $form_name;
		$tokens = $this->session->get($key, array());

		if (false !== ($pos = array_search($token, $tokens, true))) {
			unset($tokens[$pos]);
			$this->session->set($key, $tokens);

			return true;
		}

		return false;
	}

	protected function checkCsrfTokenLasting($form_name, $token)
	{
		$key = 'csrf_tokens/' . $form_name;
		$tokens = $this->session->get($key, array());

		if (false !== ($pos = array_search($token, $tokens, true))) {

			return true;
		}

		return false;
	}

	public function pager($page)
	{
		$limit = $this->userViewLimit;
		$offset = $page * $limit;
		return $offset;
	}

	public function getOrder()
	{
		$order = strval($this->request->getGet('order'));
		$order = htmlspecialchars($order, ENT_QUOTES);
		if ((!$order == 'ASC') || (!$order == 'DESC')) {
			$order = 'DESC';
		}
		return $order;
	}

	public function getPager()
	{
		$page = $this->request->getGet('pager');
		$page = htmlspecialchars($page, ENT_QUOTES);
		$page = intval($page);	// 空や不正な値は全て int 0 を返す　配列なら1
		return $page;
	}

	public function pointRounder($users, $action)
	{
		switch ($action) {
			case 'index':
				$key = 'nowPt';
				break;

			case 'general':
				$key = 'getPt';
				break;

			default:
			$key = 'nowPt';
				break;
		}

		if(array_key_exists($key, $users)) {
			$nowPt = $users[$key];
			if ($nowPt == 'undecided') {
				$users['roundPt'] = '未定';
			} else {
				$nowPt = floatval($nowPt);
				$nowPt = round($nowPt, 2);
				$users['roundPt'] = strval($nowPt);
			}
		} else {
			$i = 0;
			foreach ($users as $user) {
				$nowPt = $user[$key];
				if ($nowPt == 'undecided') {
					$users[$i]['roundPt'] = '未定';
				} else {
					$nowPt = floatval($nowPt);
					$nowPt = round($nowPt, 2);
					$users[$i]['roundPt'] = strval($nowPt);
				}
				$i++;
			}
		}
		return $users;
	}

	// 任意のユーザー1名の基本情報を生成
	// 『メイン画面』や『履歴画面 他人』のヘッダー等に使用
	public function headerUserPerson($viewUser, $usNo, $lastCalcTime)
	{
		$res = $this->db_manager->get('Status')->fetchHeaderUserPerson($viewUser, $usNo, $lastCalcTime);
		return $res;
	}

	public function usersArrayInfo($usersArray, $usNo)
	{
		$selected = array(
			'newUsers' => null,
			'following' => null,
			'followers' => null,
			'receiveFromHistory' => null,
			'toSendHistory' => null,
			'test' => null,
		);

		switch ($usersArray) {
			case 'following':
				$tableCount = $this->db_manager->get('Status')->countFollowing($usNo);
				$tableCount = $tableCount['tableCount'];

				$selected['following'] = 'selected';
				$usersNullMessage = "フォロー中のユーザーはまだいません。";
				$usersArrayMessage = "フォロー中の";
				return array($tableCount, $selected, $usersNullMessage, $usersArrayMessage);
				break;

			case 'followers':
				$tableCount = $this->db_manager->get('Status')->countFollowers($usNo);
				$tableCount = $tableCount['tableCount'];

				$selected['followers'] = 'selected';
				$usersNullMessage = "フォローされているユーザーはまだいません。";
				$usersArrayMessage = "フォローされている";
				return array($tableCount, $selected, $usersNullMessage, $usersArrayMessage);
				break;

			case 'receiveFromHistory':
				$tableName = 'tbgvn';
				$reqColumn = 'seUs';

				$tableCount = $this->db_manager->get('Admin')->tableCount($tableName, $reqColumn, $usNo);

				$selected['receiveFromHistory'] = 'selected';
				$usersNullMessage = "もらった履歴はまだありません";
				$usersArrayMessage = "もらった履歴";
				return array($tableCount[$tableName], $selected, $usersNullMessage, $usersArrayMessage);
				break;

			case 'toSendHistory':
				$tableName = 'tbgvn';
				$reqColumn = 'usNo';

				$tableCount = $this->db_manager->get('Admin')->tableCount($tableName, $reqColumn, $usNo);

				$selected['toSendHistory'] = 'selected';
				$usersNullMessage = "送った履歴はまだありません";
				$usersArrayMessage = "送った履歴";
				return array($tableCount[$tableName], $selected, $usersNullMessage, $usersArrayMessage);
				break;

			case 'test':
				$selected['test'] = 'selected';
				$tableCount = 0;
				$usersNullMessage = "testメッセージ　ユーザーはまだいません。";
				$usersArrayMessage = "testをしているユーザー";
				return array($tableCount, $selected, $usersNullMessage, $usersArrayMessage);
				break;

			default:
				// newUsers 新規ユーザー順 user数を返す
				$tableName = 'tbus';
				$tableCount = $this->db_manager->get('Admin')->tableCount($tableName);
				$tableCount = $tableCount['tbus'];
				$selected['newUsers'] = 'selected';
				$usersNullMessage = "他のユーザーはまだいません。";
				$usersArrayMessage = "登録順";
				return array($tableCount, $selected, $usersNullMessage, $usersArrayMessage);
				break;
		}
	}

	public function switchUsersArray($usersArray, $usNo, $offset, $order)
	{
		$lastCalcTime = $this->lastCalcTime;
		$limit = $this->userViewLimit;

		switch ($usersArray) {
			case 'following':
				$statuses = $this->db_manager->get('Status')->usersArrayFollowingUsers($usNo, $lastCalcTime, $limit, $offset, $order);
				return $statuses;
				break;

			case 'followers':
				$statuses = $this->db_manager->get('Status')->usersArrayFollowersUsers($usNo, $lastCalcTime, $limit, $offset, $order);
				return $statuses;
				break;

			default:
				// newUsers
				$statuses = $this->db_manager->get('Status')->usersArrayNewUsers($usNo, $lastCalcTime, $limit, $offset, $order);
				return $statuses;
				break;
		}
	}


}
