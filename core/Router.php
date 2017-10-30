<?php

// Router->compileRouters に使う値 $definitions のルール
// 基本はindex.php 以降のディレクトリ形式をコントローラーとのキ－として扱う
// PATH_INFO　index.php　より後　ベースURLより後のpathを特定 :hoge で動的パラメータ定義　GETパラメータ（ ?以降）は含まず

class Router
{
	protected $routes;

	public function __construct($definitions)
	{
		$this->routes = $this->compileRoutes($definitions);
	}

	public function compileRoutes($definitions)
	{

			// explode — 文字列を第1引数で分割し、配列化して返す
			// ltrim 文字列先頭空欄を取る、第二引数で取る文字指定可 > '/'
				// strpos — 文字列内の部分文字列が最初に現れる場所を見つける
				// 『:』がある場合はpreg_matchの引数3でkey=>valueで使える正規表現に差し替え
					// substr — 文字列の一部分を返す　　結果『:』を抜く処理をする
					// 正規表現パターンは [^/]+  『/』以外の1文字以上の文字を抽出
					//(:P<名前>パターン)で指定した名前で取得できるようになるP217

		$routes = array();
		foreach ($definitions as $url => $params) {
			$tokens = explode('/', ltrim($url, '/'));
			foreach ($tokens as $i => $token) {
				if (0 === strpos($token, ':')) {
					$name = substr($token, 1);
					$token = '(?P<' . $name . '>[^/]+)';
				}
				$tokens[$i] = $token;
			}
			$pattern = '/' . implode('/', $tokens);
			$routes[$pattern] = $params;
		}

		return $routes;
	}


	// ルーティングパラメーターの特定、マッチングを行う
	// やはり PATH_INFO を受け取る

	public function resolve($path_info)
	{
		if ('/' !== substr($path_info, 0, 1)) {
			$path_info = '/' . $path_info;
		}

		foreach ($this->routes as $pattern => $params) {
			//  #hoge# は /hoge/ と同じデリミタ
			if (preg_match('#^' . $pattern . '$#', $path_info, $matches)) {
				$params = array_merge($params, $matches);

				return $params;
			}
		}

		return false;
	}
}