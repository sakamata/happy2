<?php

class View
{
	protected $base_dir;
	protected $defaults;
	protected $layout_variables = array();

	public function __construct($base_dir, $defaults = array())
	{
		$this->base_dir = $base_dir;
		$this->defaults = $defaults;
	}

	public function setLayoutVar($name, $value)
	{
		$this->layout_variables[$name] = $value;
	}

	public function render($_path, $_variables = array(), $_layout = false)
	{
		$_file = $this->base_dir . '/' . $_path . '.php';

		extract(array_merge($this->defaults, $_variables));

		ob_start();
		ob_implicit_flush(0);

		require $_file;

		$content = ob_get_clean();

		if ($_layout) {
			$content = $this->render($_layout, array_merge($this->layout_variables, array('_content' => $content,
				)
			));
		}

		return $content;
	}

	public function escape($string)
	{
		return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
	}

	// Thanks! https://iritec.jp/web_service/6290/ and Mr. Hiroshi Tokumaru
	// 文字列をすべて uXXXX 形式に変換
	public function unicode_escape($matches)
	{
		$u16 = mb_convert_encoding($matches[0],'UTF-16','UTF-8');
		return preg_replace('/[0-9a-f]{4}/','\u$0',bin2hex($u16));
	}

	// Thanks!! http://doop-web.com/blog/archives/1182
	// ファイルpathから更新日を出力、pathにパラメータを入れcssや画像のキャッシュクリアに利用
	public function echo_filedate($filename) {
		if (file_exists($filename)) {
			echo date('YmdHis', filemtime($filename));
		} else {
			echo 'file not found';
		}
	}

	// 英数字とマイナス、ピリオド以外を uXXXX 形式でエスケープ
	// JS内で,DBやユーザー記述のPHPの値を呼び出す際にはこれを使用する
	// 使用例
	// echo $this->escape_js($data);
	public function escape_js($string)
	{
		$string = preg_replace_callback('/[^-.0-9a-zA-Z]+/u', 'View::unicode_escape', $string);
		return $this->escape($string);
	}
}
