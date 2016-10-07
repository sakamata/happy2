<?php

class Request
{
	public function isPost()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			return true;
		}
		return false;
	}

	public function getGet($name, $default = null)
	{
		if (isset($_GET[$name])) {
			return $_GET[$name];
		}
		return $default;
	}

	public function getPost($name, $default = null)
	{
		if (isset($_POST[$name])) {
			return $_POST[$name];
		}
		return $default;
	}

	public function getPostFile($name, $default = null)
	{
		if (isset($_FILES[$name])) {
			return $_FILES[$name];
		}
		return $default;
	}

	// サーバーホスト名 の文字列を確実に取得 要はlocalhost
	public function getHost()
	{
		if (!empty($_SERVER['HTTP_HOST'])) {
			return $_SERVER['HTTP_HOST'];
		}
		return $_SERVER['SERVER_NAME'];
	}

	public function isSsl()
	{
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
			return true;
		}
		return false;
	}

	public function getRequestUri()
	{
		return $_SERVER['REQUEST_URI'];
	}

	public function getBaseUrl()
	{
		$script_name = $_SERVER['SCRIPT_NAME'];

		$request_uri = $this->getRequestUri();

		if (0 === strpos($request_uri, $script_name)) {
			return $script_name;
		} else if (0 === strpos($request_uri, dirname($script_name))) {
			return rtrim(dirname($script_name), '/');
		}

		return '';
	}

	public function getHrefBase()
	{
		$href_base = '/happy2/web';
		return $href_base;
	}

	//action のリクエスト先は index.php が必須の場所と絶対不要の場所があるが理由がわからない、
	// 現状happy2/web/index.php では,必須、対処療法として、　$req_uri はindex.phpだけで使用
	public function getRequestBase()
	{
		$uri = $_SERVER['REQUEST_URI'];

		// /happy2/web/index.php or index_dev.php より後ろが無ければそのまま出力
		if ($uri === ('/happy2/web/index.php')) {
			return $uri;
		}
		if ($uri === '/happy2/web/index_dev.php') {
			return $uri;
		}

		// $_SERVER['REQUEST_URI']　にindex*.php があればそこまで出力
		if(0 < strpos($uri, 'index_dev.php')) {
			$req_base = strstr($uri, 'index_dev.php',TRUE);
			$req_base = $req_base.'index_dev.php';
			return $req_base;
		}

		if(0 < strpos($uri, 'index.php')) {
			$req_base = strstr($uri, 'index.php',TRUE);
			$req_base = $req_base.'index.php';
			return $req_base;
		}

		// index*.php が無ければ /happy2/web/index.php まで出力
		if ($_SERVER['SERVER_NAME'] == 'localhost') {
			$req_base = '/happy2/web/index_dev.php';
			return $req_base;
		}
		$req_base = '/happy2/web/index.php';
		return $req_base;
	}

	public function getPathInfo()
	{
		$base_url = $this->getBaseUrl();
		$request_uri = $this->getRequestUri();

		if (false !== ($pos = strpos($request_uri, '?'))) {
			$request_uri = substr($request_uri, 0, $pos);
		}

		$path_info = (string)substr($request_uri, strlen($base_url));

		return $path_info;
	}
}
