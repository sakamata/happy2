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
		// ToDo 一時妥協した $_SERVER から生成されるようにしたい
		$href_base = '/happy2/web';
		return $href_base;
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
