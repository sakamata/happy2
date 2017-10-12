<?php

// DBへの接続情報を管理する

class DbManager
{
	// PDOクラスのインスタンスを配列で保持
	protected $connections = array();

	protected $repositories = array();

	// テーブルごとのRepositoryクラスと接続名の対応を格納
	protected $repository_connection_map = array();

	// $name>接続を特定する名前,キー  $paramsその値、パスワードそのもの等
	public function connect($name, $params)
	{
		$params = array_merge(array(		//1
			'dsn'		=> null,
			'user'		=> '',
			'password'	=> '',
			'options'	=> array(),
		), $params);

		$con = new PDO(				//2
			$params['dsn'],
			$params['user'],
			$params['password'],
			$params['options']
		);

		// CentOS7 PHP5.4 & MariaDB5.5.5.6 では指定が必要
		$con->query("set names utf8");

		// クエリエラー時にエラーレポート　例外を投げる
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$this->connections[$name] = $con;
	}

	// 引数$name のデフォルト値を null とする、メソッド呼び出し時に　$name　を指定すれば値が入る
	public function getConnection($name = null)
	{
		if (is_null($name)) {
			// current 配列の現在の要素を返す、 ここでは最初の値 dsn? を返す
			return current($this->connections);			//4
		}

		return $this->connections[$name];
	}

	public function setRepositoryConnectionMap($repository_name, $name)
	{
		$this->repository_connection_map[$repository_name] = $name;
	}

	public function getConnectionForRepository($repository_name)
	{
		if (isset($this->repository_connection_map[$repository_name])) {
			$name = $this->repository_connection_map[$repository_name];
			$con = $this->getConnection($name);
		} else {
			$con = $this->getConnection();
		}

		return $con;
	}

	public function get($repository_name)
	{
		if (!isset($this->repositories[$repository_name])) {
			$repository_class = $repository_name . 'Repository';
			$con = $this->getConnectionForRepository($repository_name);
			// 動的なクラスを生成
			$repository = new $repository_class($con);

			$this->repositories[$repository_name] = $repository;
		}

		return $this->repositories[$repository_name];
	}

	// 接続の解放処理
	public function __destruct()
	{
		foreach ($this->repositories as $repository) {
			unset($repository);
		}

		foreach ($this->connections as $con) {
			unset($con);
		}
	}
}
