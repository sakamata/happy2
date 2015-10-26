<?php
/**
* オートロード設定を行うクラス
* bootstrap.phpで指定したファイル内クラスの一括読み込みを行う
*/
class ClassLoader
{
	protected $dirs;

	// 指定したメソッド loadClass を __autoload() の実装として登録し、loadClass($class)を実行
	public function register()
	{
		spl_autoload_register(array($this, 'loadClass'));
	}

	// 引数 $dir:str は core models フォルダ内のファイルpath指定
	public function registerDir($dir)
	{
		$this->dirs[] = $dir;
	}

	public function loadClass($class)
	{
		foreach ($this->dirs as $dir) {
			$file = $dir . '/' . $class . '.php';
			if (is_readable($file)) {
				require $file;

				return;
			}
		}
	}

}