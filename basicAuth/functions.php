<?php
// mpyw Thanks! https://github.com/mpyw-yattemita/php-auth-examples
/**
 * Basic認証を要求するページの先頭で使う関数
 * 初回時または失敗時にはヘッダを送信してexitする
 *
 * @return string ログインしたユーザ名
 */
function require_basic_auth()
{
    // 事前に生成したユーザごとのパスワードハッシュの配列
    $hashes = [
        'admin929' => '$2y$10$NpDBxOnjipveNSZFdMt7peGqY.hascB09AKOm9Kn/S20p0SNW9UiK',
    ];
	// error_log($_SERVER['PHP_AUTH_USER']);
	// error_log('!!!in!!!!!');
	// error_log($_SERVER['PHP_AUTH_PW']);

    if (
        !isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) ||
        !password_verify(
            $_SERVER['PHP_AUTH_PW'],
            isset($hashes[$_SERVER['PHP_AUTH_USER']])
                ? $hashes[$_SERVER['PHP_AUTH_USER']]
                : '$2y$10$abcdefghijklmnopqrstuv' // ユーザ名が存在しないときだけ極端に速くなるのを防ぐ
        )
    ) {
        // 初回時または認証が失敗したとき
		error_log(var_dump($_SERVER));
        header('WWW-Authenticate: Basic realm="Enter username and password."');
        header('Content-Type: text/plain; charset=utf-8');
		// error_log($_SERVER['PHP_AUTH_USER']);
		// error_log('!!!!!!first or auth_miss!!!!!!');
		// error_log($_SERVER['PHP_AUTH_PW']);
        exit('このページを見るにはログインが必要です');
    }
	// error_log($_SERVER['PHP_AUTH_USER']);
	// error_log('!!!!!!success!!!!!!');
	// error_log($_SERVER['PHP_AUTH_PW']);

    // 認証が成功したときはユーザ名を返す
    return $_SERVER['PHP_AUTH_USER'];
}

/**
 * htmlspecialcharsのラッパー関数
 *
 * @param string $str
 * @return string
 */
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
