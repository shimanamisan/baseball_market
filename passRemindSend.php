<?php

// 共通関数
require('function.php');

debug('=============================');
debug('パスワード再設定ページ passRemaindSend.php');
debug('=============================');
debugLogStart();

//ログイン認証はなし（ログインできない人が使うので）

//================================
// ログイン画面処理
//================================
// post送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります。 passRemaindSend.php');
  debug('POST情報はこれです passRemaindSend.php：' . print_r($_POST,true));

  // 変数にPOST情報を代入
  $email = $_POST['email'];
  
  // 未入力チェック
  validRequired($email, 'email');

  if(empty($err_msg)){

    debug('未入力チェックオーケーです passRemaindSend.php');

    //emailの形式チェック
    validEmail($email, 'email');
    //emailの最大文字数チェック
    validMaxLen($email, 'email');

    if(empty($err_msg)){
      debug('バリデーションオーケーです passRemaindSend.php');

      // 例外処理
      try{
        // DBへ接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
        $data = array(':email' => $email);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        // クエリ結果の値を取得
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // 配列で取得されているか確認
        debug(print_r($result, true));

        // EmailがDBに登録されている場合
        if($stmt && array_shift($result)){
          debug('クエリ成功です。DB登録ありあります。 passRemaindSend.php');
          $_SESSION['msg_success'] = SUC03;

          $auth_key = makeRandKey(); // 認証キー生成

          //メールを送信
          $from = 'itsup-info@shimanamisan.com';
          $to = $email;
          $subject = '【パスワード再発行】｜BASEBALL ITEMカスタマーセンター';
           //EOT:EndOfTextの略。他にもよく使われるものでEOF(EndOfFile)等がある。ABCでも何でもいい。先頭の<<<の後の文字列と合わせること。最後のEOTの前後に空白など何も入れては駄目！
           //EOT内の半角空白も全てそのまま半角空白として扱われるのでインデントはしないこと
$comment = <<<EOT
本メールアドレス宛にパスワード再発行のご依頼がありました。
下記のURLにて認証キーをご入力頂くとパスワードが再発行されます。

パスワード再発行認証キー入力ページ：http://localhost/programming/baseball_market/passRemindRecieve.php
認証キー：{$auth_key}
※認証キーの有効期限は30分となります

認証キーを再発行されたい場合は下記ページより再度お手続きをお願い致します。
http://localhost/programming/baseball_market/passRemaindSend.php

*****************************************************
BASEBALL ITEMカスタマーセンター
URL：http://localhost/programming/baseball_market/
Email：itsup-info@shimanamisan.com
*****************************************************
EOT;

          sendMail($from, $to, $subject, $comment);

          // 認証に必要な情報をセッションへ保存
          $_SESSION['auth_key'] = $auth_key;
          $_SESSION['auth_email'] = $email;
          // 現在時刻より30分後のUNIXタイムスタンプを入れる
          $_SESSION['auth_key_limit'] = time()+(60*30);
          debug('セッション変数の中身 passRemaindSend.php：' .print_r($_SESSION, true));

          header("Location:passRemindRecieve.php"); //認証キー入力ページへ
          exit();

        }else{
          debug('クエリに失敗したか、DBに登録のないメールアドレスが入力されました');
          $err_msg['common'] = MSG07;
        }

      }catch (Exception $e){
          error_log('エラー発生：' .$e->getMessage());
          $err_msg['common'] = MSG07;
      }
    }
  }
}
?>

<?php
$siteTitle = 'パスワード再発行';
require('head.php');
?>

<body class="page-signup page-1colum">

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
           <p>ご指定のメールアドレス宛にパスワード再発行用のURLと認証キーをお送り致します。</p>
           <div class="area-msg">
              <?php
              if(!empty($err_msg['common'])) echo $err_msg['common'];
              ?>
           </div><!-- area-msg -->
              <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
                Email
                <input type="text" name="email" value="<?php echo getFormData('email'); ?>">
              </label>
            <div class="area-msg">
              <?php
              if(!empty($err_msg['email'])) echo $err_msg['email'];
              ?>
            </div><!-- area-msg -->
            <div class="btn-container">
              <input type="submit" class="btn btn-mid" value="送信する">
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