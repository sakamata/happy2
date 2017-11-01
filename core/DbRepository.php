<?php

// DBへのアクセスを行う
// テーブルごとに子クラスを作成させる
// 抽象クラスで定義
abstract class DbRepository
{
	protected $con;

	public function __construct($con)
	{
		$this->setConnection($con);
	}

	public function setConnection($con)
	{
		$this->con = $con;
	}

	// PDOのプレースホルダでクエリを発行 PDOStatementクラスのインスタンスを取得する
	public function execute($sql, $params = array())
	{
		$stmt = $this->con->prepare($sql);
		$stmt->execute($params);

		return $stmt;
	}

	public function fetch($sql, $params = array())
	{
		// FETCH_ASSOC　=> PDOの取得結果を連想配列で受け取る
		return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
	}

	public function fetchAll($sql, $params = array())
	{
		return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
	}


	public function fetchAll_bindParam($sql, $params = array())
	{
		$stmt = $this->con->prepare($sql);
		foreach ($params as $key => $values) {
			// PDOexecuteバグ対策 :limit が文字列で渡る為INTを指定
			// ***ToDo*** 不完全、limit でも :hoge 等の任意の値が渡る
			// ***ToDo*** POSTされた値を拾った時点で型確認をさせる
			if ($key == ':limit' || ':offset') {
				$stmt->bindParam($key, $values, PDO::PARAM_INT);
			}
			$stmt->bindParam($key, $values);
		}
		$stmt->execute();
		return $stmt->fetchall(PDO::FETCH_ASSOC);
	}

}
