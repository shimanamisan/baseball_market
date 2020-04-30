<?php
//================================
// ログイン認証・自動ログアウト
//================================

// ログインしている場合
if(!empty($_SESSION['login_date'])){
    debug('ログイン済みユーザーです。 auth.php');

    // 現在日時が最終ログイン+有効期限を超えていた場合
    if( ($_SESSION['login_date'] + $_SESSION['login_limit']) < time() ){
        debug('ログイン有効期限を超えています。 auth.php');

        // セッションの削除（ログアウト処理）
        session_destroy();
        // ログインページへ遷移
        header("Location:login.php");
        // ページ遷移後、後続の処理を行わにようにする
        exit();
    }else{
        debug('ログイン有効期限内です。 auth.php');
        // 最終ログイン日時を現在に変更
        $_SESSIION['login_date'] = time();

        // login.phpにアクセスしてきた場合に、マイページへ遷移させる。ここの条件分岐がないと無限ループになってしまう
        // $_SERVER['PHP_SELF']はURLのパスを返す。さらにbasenameメソッドを使うことで「login.php」といったファイル名を抜粋する
        if(basename($_SERVER['PHP_SELF']) === 'login.php'){
            debug('アクセスしたパスを確認 auth.php：'. print_r($_SERVER['PHP_SELF']));
            debug('マイページへ遷移します auth.php');
            // マイページへ遷移
            header("Location:mypage.php");
            exit();
        }
    }

// 未ログインユーザーの処理
}else{
    debug('未ログインユーザーです auth.php');
    // 未ログインユーザーが、ログイン認証が必要なページにアクセスしていたらログインページに遷移させる
    if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
        // ログインページへ遷移
        header("Location:login.php");
        exit();
    }
}

?>