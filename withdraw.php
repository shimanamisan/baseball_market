<?php

// 共通関数
require('function.php');

debug('===============================');
debug('=== 退会ページの処理 withdrow.php ===');
debug('===============================');
debugLogStart();

// ログイン認証
require('auth.php');

//================================
// ログイン画面処理
//================================
// post送信されていた場合

if(!empty($_POST)){
  debug('POST送信があります。');
  
  // 例外処理
  try{
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    // 物理削除ではなく論理削除を行う：delete_flgが1だったものは削除されたものと判断する
    // UPDATEというのはそれぞれのテーブルで行わなければならないので、3つSQL文を作る
    $sql1 = 'UPDATE users SET delete_flg = 1 WHERE id = :us_id';
    $sql2 = 'UPDATE product SET delete_flg = 1 WHERE user_id = :us_id';
    $sql3 = 'UPDATE `like` SET delete_flg = 1 WHERE user_id = :us_id';
    //データ流し込み
    $data = array(':us_id' => $_SESSION['user_id']);
    //クエリ実行
    $stmt1 = queryPost($dbh, $sql1, $data);
    $stmt2 = queryPost($dbh, $sql2, $data);
    $stmt3 = queryPost($dbh, $sql3, $data);

    // クエリ実行成功の場合（ユーザーと商品テーブルのSQLが成功した場合）
    if($stmt1 && $stmt2){
      // セッション削除
      session_destroy();
      debug('セッション変数の中身：'. print_r($_SESSION,true));
      debug('トップページへ遷移します');
      // 退会しましたというメッセージを入れる
      header('Location:index.php');
      exit();
    }else{
      debug('クエリが失敗しました');
      $err_msg['common'] = MSG07;
    }
  }catch (Exception $e){
      error_log('エラー発生：' . $e->getMessage());
      $err_msg['common'] = MSG07;
  }
}
debug(' ==== 退会ページの処理終了 withdrow.php ====');

?>

<?php
$siteTitle = '退会';
require('head.php');
?>

<style>
.form .btn{
  float: none;
}
</style>

<body class="page-withdraw page-1colum">

    <!-- ヘッダー -->
      <?php
      require('header.php');
      ?>

    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">
      <!-- Main -->
      <section id="main" >
        <div class="form-container">
          <form action="" method="post" class="form">
            <h2 class="title">退会</h2>
            <?php
              if(!empty($err_msg['common'])) echo $err_msg['common']
            ?>
            <div class="btn-container">
              <input type="submit" class="btn post-btn btn-mid" value="退会する" name="submit" onClick="return withdraw()">
            </div>
          </form>
        </div>
        <a href="mypage.php">&lt; マイページに戻る</a>
      </section>
    </div>

    <!-- footer -->
    <?php
    require('footer.php');    
    ?>
