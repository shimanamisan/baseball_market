<?php

//共通変数・関数読み込み

require('function.php');

debug('===============================');
debug('=== お気に入り登録 Ajax.php ===');
debug('===============================');
debugLogStart();

//=============================
//Ajax処理
//=============================
debug('POST送信されています'. $_POST['productId']);
debug('    ');
//POSTがあり、ユーザーIDがありログインしている場合
if (isset($_POST['productId']) && isset($_SESSION['user_id']) && isLogin()) {
    // productIdというのは商品のID(商品レコード)で、商品レコードは0から採番されるので0も判定に含むようにする。user_idも0から採番されるのでissetで判定する
    // SESSIONにユーザーIDが含まれていればログインしていることと見なされるが、厳密に判定を行うためisLoginも判定に加える
    // auth.phpだとログインされてなかったらログインページに遷移してしまうので、そうならないようにisLogin関数でデータベースに登録しないだけにしておく
    // 関数の頭文字に「is」とついていたら、returnでtrueかfalseだけを返すんだなとわかるように、決まりみたいな事がある
    debug('=== POST送信があります Ajax.php ===');
    debug('ログイン済みのユーザーなので、SESSIONにユーザーIDが格納されています。ユーザーID： '. $_SESSION['user_id']);
    debug('    ');
    $p_id = $_POST['productId'];
    debug('商品ID：'.print_r($p_id, true));
    debug('    ');
    debug('ユーザーID：'.print_r($_SESSION['user_id'], true));
    debug('    ');
  
    // 例外処理
    try {
        // DBへ接続
        $dbh = dbConnect();
        // レコードがあるか検索
        // likeという単語はLIKE検索というSQLの命令文で使われているため、そのままでは使えないのでバッククォート `` (＠キーをshift押しながら)で囲む
        $sql = 'SELECT * FROM `like` WHERE product_id = :p_id AND user_id = :u_id';
        $data = array(':u_id' => $_SESSION['user_id'], ':p_id' => $p_id);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        $resultCount = $stmt->rowCount();
        debug('rowCount関数の結果です：'.print_r($resultCount, true));
        debug('    ');
        // レコードが1件でもある場合
        if (!empty($resultCount)) {
            // レコードを削除する
            $sql = 'DELETE FROM `like` WHERE product_id = :p_id AND user_id = :u_id';
            $data = array(':u_id' => $_SESSION['user_id'], ':p_id' => $p_id);
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
            debug('レコードが存在していたのでお気に入り削除の処理 ajax.php：'.print_r($stmt, true));
            debug('    ');
            // レスポンスの content-type を application/json に指定する。指定していないと text/html で返す
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode([
                  'response' => 'remove'
            ]);
            return;
            debug('===== Ajax処理終了です =====');
        } else {
            // レコードを挿入する
            $sql = 'INSERT INTO `like` (product_id, user_id, create_date) VALUES (:p_id, :u_id, :date)';
            $data = array(':u_id' => $_SESSION['user_id'], ':p_id' => $p_id, ':date' => date('Y-m-d H:i:s'));
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
            debug('レコードなかったので、お気に入り追加の処理 ajax.php：'.print_r($stmt, true));
            debug('    ');
            // レスポンスの content-type を application/json に指定する。指定していないと text/html で返す
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode([
                    'response' => 'add'
            ]);
            return;
            debug('===== Ajax処理終了です =====');
        }
    } catch (Exception $e) {
        error_log('エラー発生：' .$e->getMessage());
    }
} else {
    debug('ログインしていません');
    debug('    ');
    // ログインしていないときの処理
    // レスポンスの content-type を application/json に指定する。指定していないと text/html で返す
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
      'response' => 'no_login'
    ]);
    return;
    debug('===== Ajax処理終了です =====');
}

debug('===== Ajax処理終了です =====');
