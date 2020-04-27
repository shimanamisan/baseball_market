<?php
// 共通関数
require('function.php');

debug('=============================');
debug('=== ここはログインページです login.php ===');
debug('=============================');
debugLogStart();

// ログイン認証
require('auth.php');

//================================
// ログイン画面処理
//================================
// POSTされていた場合
if(!empty($_POST)){
  debug('POST送信されています login.php');
  // 変数にユーザー情報を格納
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  // ショートハンド（省略法）とう書き方
  // チェックボックスは真偽値で入ってくる
  $pass_save = (!empty($_POST['pass_save'])) ? true : false ;
  debug('チェックボックスの真偽値を確認 login.php' . ' : ' .$pass_save);

  // 未入力チェック
  validRequired($email, 'email');
  validRequired($pass, 'pass');

  // emailの形式チェック
  validEmail($email, 'email');
  // emailの最大文字数チェック
  validMaxLen($email, 'email');

  // パスワードの半角英数字チェック
  validHalf($pass, 'pass');
  // パスワードの最大文字数チェック
  validMaxLen($pass, 'pass');
  // パスワードの最小文字数チェック
  validMinLen($pass, 'pass');

  // エラーメッセージ変数が空であればバリデーションOK
  if(empty($err_msg)){
    debug('バリデーションOKです login.php');
    
    //例外処理
    try {
      // DBへ接続
      $dbh = dbConnect();
      // SQL文作成
      $sql = 'SELECT password, id FROM users WHERE email = :email AND delete_flg = 0';
      // プレースホルダーを使用
      $data = array(':email' => $email);
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      // クエリ結果の値を取得
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      
      // パスワード照合
      //password_verify:ハッシュ化したパスワードと入力したパスワードを比べるための関数
      if(!empty($result) && password_verify($pass, array_shift($result))){
        debug('パスワードがマッチしました login.php');
        //ログイン有効期限（デフォルトを１時間とする）
        $sesLimit = 60*60;
        // 最終ログイン日時を現在日時に
        $_SESSION['login_date'] = time();
        // ログイン保持にチェックがある場合
        if($pass_save){
          debug('ログイン保持にチェックがあります login.php');
          // ログイン有効期限を30日にしてセット
          $_SESSION['login_limit'] = $sesLimit * 24 * 30;
        }else{
          debug('ログイン保持にチェックはありません login.php');
          // 次回からログイン保持しないので、ログイン有効期限を1時間後にセット
          $_SESSION['login_limit'] = $sesLimit;
        }
        // ユーザーIDを格納
        $_SESSION['user_id'] = $result['id'];
        debug('セッション変数の中身 login.php：'.print_r($_SESSION,true));
        debug('マイページへ遷移します login.php');
        //マイページへ
        header("Location:mypage.php");
        exit;

      }else{
        debug('パスワードがアンマッチです login.php');
        $err_msg['common'] = MSG09;
      }

    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}
debug(' **** 画面表示処理終了 **** login.php');
?>

<?php
  $siteTitle = 'ログイン';
  require('head.php');
?>

  <body class="page-login page-1colum">

    <!-- ヘッダー -->
    <?php
      require('header.php');
    ?>
    
      <p id="js-show-msg" style="display:none; line-height:90px;" class="msg-slide">
        <?php echo getSessionFlash('msg_success'); ?>
      </p>

    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">

      <!-- Main -->
      <section id="main" >

       <div class="form-container">
        
       <form action="" method="post" class="form">
           <h2 class="title">ログイン</h2>
           <div class="area-msg">
             <?php 
              if(!empty($err_msg['common'])) echo $err_msg['common'];
             ?>
           </div>
           <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
            <span class="form-label">メールアドレス</span>
             <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
           </label>
           <div class="area-msg">
             <?php 
             if(!empty($err_msg['email'])) echo $err_msg['email'];
             ?>
           </div>
           <label class="<?php if(!empty($err_msg['pass'])) echo 'err'; ?>">
           <span class="form-label">パスワード</span>
             <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
           </label>
           <div class="area-msg">
             <?php 
             if(!empty($err_msg['pass'])) echo $err_msg['pass'];
             ?>
           </div>
           <label>
             <input type="checkbox" name="pass_save">次回ログインを省略する
           </label>
            <div class="btn-container">
              <input type="submit" class="submit-btn btn btn-mid" value="ログイン">
            </div>
            パスワードを忘れた方は<a href="passRemindSend.php">コチラ</a>
         </form>

       </div>

      </section>

    </div>

<?php
  require('footer.php');
?>
