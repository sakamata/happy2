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

	protected $lastCalcTime;	// 最終集計時間
	protected $allUserCount;	// 全ユーザー数
	protected $userViewLimit; // ユーザー表示数
	protected $postSecond; // クリック情報の定期POST秒
	protected $calcCount; // 集計回数
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

}
