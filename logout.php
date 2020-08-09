<?php
// 共通関数
require('function.php');

debug('=============================');
debug('ここはログアウトページです logout.php');
debug('=============================');
debugLogStart();

debug('ログアウトします logout.php');
// セッションを削除（ログアウト）
session_destroy();
debug('ログインページへ遷移します logout.php');
// ログインページへ
header("Location:login.php");

?>