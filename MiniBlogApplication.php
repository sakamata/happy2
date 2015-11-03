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
		// URL  /account/:action => account/signup
		return array(
			'/account'
				=> array('controller' => 'account', 'action' => 'index'),
			'/account/:action'
				=> array('controller' => 'account'),
			'/'
				=> array('controller' => 'status', 'action' => 'index'),
		);
	}

	protected function configure()
	{
		$this->db_manager->connect('master', array(
			'dsn' => 'mysql:dbname=happy2;host=localhost',
			'user' => 'root',
			'password' => 'penpen',
		));
	}
}
