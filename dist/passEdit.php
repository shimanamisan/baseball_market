<?php

// 共通関数
require('function.php');

debug('===============================');
debug('=== パスワード変更ページ passEdit.php ===');
debug('===============================');
debugLogStart();

// ログイン認証
require('auth.php');


//===================
//画面処理
//===================
// DBからユーザーデータを取得
$userData = getUser($_SESSION['user_id']);
debug('取得したユーザー情報はこれです passEdit.php：' . print_r($userData,true));

// POST送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります。 passEdit.php');
  debug('POST情報はこれです passEdit.php：' . print_r($_POST,true));

    // 変数にユーザー情報を代入
    $pass_old = $_POST['pass_old'];
    $pass_new = $_POST['pass_new'];
    $pass_new_re = $_POST['pass_new_re'];

    // 未入力チェック
    validRequired($pass_old, 'pass_old');
    validRequired($pass_new, 'pass_new');
    validRequired($pass_new_re, 'pass_new_re');

    if(empty($err_msg)){
      debug('未入力チェックOKです passEdit.php');

      // 古いパスワードのチェック
      validPass($pass_old, 'pass_old');

      // 新しいパスワードのチェック
      validPass($pass_new, 'pass_new');

      // 古いパスワードとDBパスワードを照合（DBに入っているデータと同じであれば、半角数字や最大文字数のチェックは行う必要はない）
      if(!password_verify($pass_old, $userData['password'])){
        $err_msg['pass_old'] = MSG12;
      }

      // 新しいパスワードと古いパスワードが同じかチェック
      if($pass_old === $pass_new){
        $err_msg['pass_new'] = MSG13;
      }

        //パスワードとパスワード再入力が合っているかチェック
        //ログイン画面では最大、最小チェックもしていたがパスワードの方でチェックしているので実は必要ない）
        validMatch($pass_new, $pass_new_re, 'pass_new_re');

        if(empty($err_msg)){
          debug('バリデーションOK passEdit.php');

          //例外処理
          try{
            //DBへ接続
            $dbh = dbConnect();
            //SQL文作成
            $sql = 'UPDATE users SET password = :pass WHERE id = :id';
            $data = array(':id' => $_SESSION['user_id'], ':pass' => password_hash($pass_new, PASSWORD_DEFAULT));
            //クエリ実行
            $stmt = queryPost($dbh, $sql, $data);

            //クエリ成功の場合
            if($stmt){
              $_SESSION['msg_success'] = SUC01;

              //メールを送信
              $username = ($userData['username']) ? $userData['username'] : '名前無し';
              $from = 'itsup-info@shimanamisan.com';
              $to = $userData['email'];
              $subject = '【パスワード変更通知】 ｜ BASEBALL ITEMカスタマーセンター';
            // EOT:EndOfTextの略。他にもよく使われるものでEOF(EndOfFile)等がある。ABCでも何でもいい。先頭の<<<の後の文字列と合わせること。最後のEOTの前後に空白など何も入れては駄目！
            // EOT内の半角空白も全てそのまま半角空白として扱われるのでインデントはしないこと
          $comment = <<<EOT
【パスワード変更メールです。心当たりにない場合はこのメッセージを削除してください。】

{$username}　さん
パスワードが変更されました。

*****************************************************
BASEBALL ITEMカスタマーセンター
URL：http://localhost/programming/baseball_market/
Email：itsup-info@shimanamisan.com
*****************************************************
EOT;

              sendMail($from, $to, $subject, $comment);

              header("Location:mypage.php"); //マイページへ遷移
              exit();
            }

          }catch (Exceptoin $e){
            error_log('エラー発生：' . $e->getMessage());
            $err_msg['common'] = MSG07;
      }
    }
  }
}

?>

<?php
    $siteTitle = 'パスワード変更';
    require('head.php');
    ?>

   <body class="page-passEdit page-2colum page-logined">

    <!-- ヘッダー -->
    <?php
    require('header.php');
    ?>


    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">
      <h1 class="page-title">パスワード変更</h1>
      <!-- Main -->
      <section id="main" >
        <div class="form-container">
          <form action="" method="post" class="form">
             <div class="area-msg">
              <?php
              echo getErrMsg('common');
              ?>
            </div><!-- area-msg -->
              <label class="<?php if(!empty($err_msg['pass_old'])) echo 'err'; ?>">
                古いパスワード
                <input type="password" name="pass_old" value="<?php echo getFormData('pass_old'); ?>">
              </label>    
            <div class="area-msg">
            <?php
              echo getErrMsg('pass_old');
              ?>
            </div><!-- area-msg -->
              <label class="<?php if(!empty($err_msg['pass_new'])) echo 'err'; ?>">
                新しいパスワード
                 <input type="password" name="pass_new" value="<?php echo getFormData('pass_new'); ?>">
              </label>
            <div class="area-msg">
              <?php
              echo getErrMsg('pass_new');
              ?>
            </div><!-- area-msg -->
              <label class="<?php if(!empty($err_msg['pass_new_re'])) echo 'err'; ?>">
                新しいパスワード（再入力）
                <input type="password" name="pass_new_re" value="<?php echo getFormData('pass_new_re'); ?>">
              </label>
              <div class="area-msg">
              <?php
              echo getErrMsg('pass_new_re');
              ?>
            </div><!-- area-msg -->

            <div class="btn-container">
              <input type="submit" class="btn post-btn btn-mid" value="変更する">
            </div>
          </form>
        </div>
      </section>
      
        <!-- サイドバー -->
      <?php
      require('sidebar_mypage.php');    
      ?>
      
    </div>

  <!-- footer -->
  <?php
  require('footer.php');    
  ?>
