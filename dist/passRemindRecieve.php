<?php

// 共通変数
require('function.php');

debug('=============================');
debug('=== ここはパスワード再発行認証キー入力ページです passRemaindRecieve.php ===');
debug('=============================');
debugLogStart();

// ログイン認証はなし（ログインできない人が使うので）

// セッションに認証キーがあるか確認、なければリダイレクト
if(empty($_SESSION['auth_key'])){
  header("Location:passRemindSend.php"); // 認証キー送信ページへ
  exit();
}

//================================
// ログイン画面処理
//================================
// post送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります。 passRemaindRecieve.php');
  debug('POST情報です passRemaindRecieve.php ：' . print_r($_POST,true));

  // 変数に認証キーを代入
  $auth_key = $_POST['token'];
  
  // 未入力チェック
  validRequired($auth_key, 'token');

  if(empty($err_msg)){
    debug('未入力チェックOKです passRemaindRecieve.php');

      // 固定長チェック
      validLength($auth_key, 'token');
      // 半角チェック
      validHalf($auth_key, 'token');
   
      if(empty($err_msg)){
        debug('文字形式のチェックOKです passRemaindRecieve.php');

        // 認証キーのバリデーション
        if($auth_key !== $_SESSION['auth_key']){
          $err_msg['common'] = MSG15;
        }
        // 認証キーが有効期限内かチェック
        if(time() > $_SESSION['auth_key_limit']){
          $err_msg['common'] = MSG16;
        }

        // バリデーションチェックが通ってればここからの処理
        if(empty($err_msg)){
          debug('バリデーションチェックOKです。 passRemaindRecieve.php');

          $pass = makeRandKey(); // パスワード生成

          // 例外処理
          try{
            // DBへ接続
            $dbh = dbConnect();
            // SQL文作成
            $sql = 'UPDATE users SET password = :pass WHERE email = :email AND delete_flg = 0';
            $data = array(':email' => $_SESSION['auth_email'], ':pass' => password_hash($pass, PASSWORD_DEFAULT));
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
        
            // クエリ成功の場合
            if($stmt){
              debug('クエリ成功です。 passRemaindRecieve.php');

              // メールを送信
              $from = 'itsup-info@shimanamisan.com';
              $to = $_SESSION['auth_email'];
              $subject = '【パスワード変更通知】 ｜ BASEBALL ITEMカスタマーセンター';
              // EOT:EndOfTextの略。他にもよく使われるものでEOF(EndOfFile)等がある。ABCでも何でもいい。先頭の<<<の後の文字列と合わせること。最後のEOTの前後に空白など何も入れては駄目！
              // EOT内の半角空白も全てそのまま半角空白として扱われるのでインデントはしないこと
$comment = <<<EOT
本メールアドレス宛にパスワード再発行のご依頼がありました。
下記のURLにて再発行パスワードをご入力頂き、ログインしてください。

ログインページ：http://localhost/programming/baseball_market/login.php

再発行パスワード：{$pass}

※ログイン後、パスワードの変更をお願い致します。

*****************************************************
BASEBALL ITEMカスタマーセンター
URL：http://localhost/programming/baseball_market/
Email：itsup-info@shimanamisan.com
*****************************************************
EOT;

              sendMail($from, $to, $subject, $comment);

              // セッション削除
              session_unset();
              $_SESSION['msg_success'] = SUC03;
              debug('セッション変数の中身 passRemaindRecieve.php：' .print_r($_SESSION, true));
              header("Location:login.php"); //ログインページへ
              exit();

            }else{
              debug('クエリに失敗しました');
              $err_msg['common'] = MSG07;
            }

          }catch (Exception $e){
              error_log('エラー発生：' .$e->getMessage());
              $err_msg['common'] = MSG07;
          }
        }
    }
  }
}
debug('===============================');
debug('=== 処理終了 passRemaindRecieve.php ===');
debug('===============================');
?>
<?php
$siteTitle = 'パスワード再発行認証';
require('head.php'); 
?>

  <body class="page-signup page-1colum">

    <!-- ヘッダー -->
    <?php
      require('header.php'); 
    ?>
    <p id="js-show-msg" class="msg-slide msg-success">
          <?php echo getSessionFlash('msg_success'); ?>
    </p>
    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">

      <!-- Main -->
      <section id="main" >

        <div class="form-container">

          <form action="" method="post" class="form">
            <p>ご指定のメールアドレスお送りした【パスワード再発行認証】メール内にある「認証キー」をご入力ください。</p>
            <div class="area-msg">
             <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
            </div>
            <label class="<?php if(!empty($err_msg['token'])) echo 'err'; ?>">
              認証キー
              <input type="text" name="token" value="<?php echo getFormData('token'); ?>">
            </label>
            <div class="area-msg">
             <?php if(!empty($err_msg['token'])) echo $err_msg['token']; ?>
            </div>
            <div class="btn-container">
              <input type="submit" class="btn post-btn btn-mid" value="再発行する">
            </div>
          </form>
        </div>
        <a href="passRemindSend.php">&lt; パスワード再発行メールを再度送信する</a>
      </section>

    </div>

    <!-- footer -->
    <?php
    require('footer.php'); 
    ?>