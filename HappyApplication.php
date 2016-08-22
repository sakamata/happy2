<?php

class HappyApplication extends Application
{
	protected $login_action = array('account', 'signin');

	public function getRootDir()
	{
		return dirname(__FILE__);
	}

	protected function registerRoutes()
	{
		//Router の foreach (array_expression as $key => $value) で使用
		// URL  /account/:action =>
		// 				account/signup
		// 				account/signin
		// 				account/signout
		return array(
			'/'
				=> array('controller' => 'status', 'action' => 'index'),
			'/account'
				=> array('controller' => 'account', 'action' => 'index'),
			'/account/:action'
				=> array('controller' => 'account'),
			'/admin/signin'
				=> array('controller' => 'admin', 'action' => 'signin'),
			'/admin/'
				=> array('controller' => 'admin', 'action' => 'index'),
			'/admin/:action'
				=> array('controller' => 'admin'),
			'/admin/calc'
				=> array('controller' => 'admin', 'action' => 'calc'),
			'/ajaxPost'
				=> array('controller' => 'ajaxPost', 'action' => 'index'),
			'/ajaxPost/:action'
				=> array('controller' => 'ajaxPost'),
			'/account/profile'
				=> array('controller' => 'account', 'action' => 'profile'),
		);
	}

	protected function configure()
	{
		switch($_SERVER["HTTP_HOST"]){
			case "localhost":
				$path = 'C:xampp/htdocs/hidden/info.php';
				break;

			case "160.16.57.194":
				$path = './info.php';
				break;

			case "happy-project.org":
				$path = './info.php';
				break;

			default:
				$path = './info.php';
		};

		require $path;

		$this->db_manager->connect('master', array(
			'dsn' => $dsn,
			'user' => $user,
			'password' => $pass,
		));
	}
}
