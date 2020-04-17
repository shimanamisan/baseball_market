<?php

// 共通関数
require('function.php');

//post送信されていた場合
if(!empty($_POST)){
  
  // 変数にユーザー情報を代入
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];

  // 未入力チェック
  validRequired($email, 'email');
  validRequired($pass, 'pass');
  validRequired($pass_re, 'pass_re');
   
  // バリデーションエラーがない場合
  if(empty($err_msg)){

    // emailの形式チェック
    validEmail($email, 'email');
    // emailの最大文字数チェック
    validMaxLen($email, 'email');
    // email重複チェック
    validEmailDup($email);

    // パスワードの半角英数字チェック
    validHalf($pass, 'pass');
    // パスワードの最大文字数チェック
    validMaxLen($pass, 'pass');
    // パスワードの最小文字数チェック
    validMinLen($pass, 'pass');

    // パスワード（再入力）の最大文字数チェック
    validMaxLen($pass_re, 'pass_re');
    // パスワード（再入力）の最小文字数チェック
    validMinLen($pass_re, 'pass_re');
    
    // バリデーションエラーがない場合
    if(empty($err_msg)){

      // パスワードとパスワード再入力が合っているかチェック
      validMatch($pass, $pass_re, 'pass_re');
      
      //バリデーションエラーがない場合
      if(empty($err_msg)){

        // 例外処理
        try{
          // DBへ接続
          $dbh = dbConnect();
          // SQL作成
          $sql = 'INSERT INTO users (email,password,login_time,create_date) VALUES(:email,:pass,:login_time,:create_date)';
          $data = array(':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT),
                        ':login_time' => date('Y-m-d H:i:s'),
                        ':create_date' => date('Y-m-d H:i:s'));
          // クエリ実行
          $stmt = queryPost($dbh, $sql, $data);
          // クエリ成功の場合
          if($stmt){
            // ログイン有効期限（デフォルトを1周間とする）
            $sesLimit = 60 * 60;
            // 最終ログイン日時を現在日時に
            $_SESSION['login_date'] = time();
            $_SESSION['login_limit'] = $sesLimit;
            // ユーザーIDを格納
            // ユーザーIDはDBに新規登録された際にオートインクリメントされて順に登録されていく
            // この時点ではどんなIDかわからないので、SQL文で拾ってもよいが効率が悪い
            // dbConnect()した際にPDOオブジェクトを取ってきているのでそれを使う
            // そのオブジェクトの中のまとまった処理の関数（メソッド）でlastInsertId()というものがあるのでこれを呼び出して使う
            // ここで取得したユーザーIDを$_SESSIONに連想配列で格納している
            $_SESSION['user_id'] = $dbh->lastInsertId();

            debug('セッション変数の中身：'. print_r($_SESSION,true));
            
            header("Location:mypage.php"); //マイページへ
          }

        }catch(Exception $e){
          error_log('例外エラー発生：'. $e->getMessage());
          $err_msg['common'] = MSG07;
        }

        //SQL文（クエリー作成）
        $stmt = $dbh->prepare('INSERT INTO users (email,pass,login_time) VALUES (:email,:pass,:login_time)');

        //プレースホルダに値をセットし、SQL文を実行
        $dbRst = $stmt->execute(array(':email' => $email, ':pass' => $pass, ':login_time' => date('Y-m-d H:i:s')));
        
        //SQL実行結果が成功の場合
        if($dbRst){
          header("Location:mypage.html"); //マイページへ
        }
      }
    }
  }
}

?>
<?php
  $siteTitle = 'HOME';
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

          <form action="" class="form" method="post">
            <h2 class="title">ユーザー登録</h2>
            <div class="area-msg">
              <?php 
                if(!empty($err_msg['common'])) echo $err_msg['common'];
              ?>
            </div>
            <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
              Email
              <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
            </label>
            <div class="area-msg">
              <?php 
              if(!empty($err_msg['email'])) echo $err_msg['email'];
              ?>
            </div>
            <label class="<?php if(!empty($err_msg['pass'])) echo 'err'; ?>">
              パスワード <span style="font-size:12px">※英数字6文字以上</span>
              <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
            </label>
            <div class="area-msg">
              <?php 
              if(!empty($err_msg['pass'])) echo $err_msg['pass'];
              ?>
            </div>  
            <label class="<?php if(!empty($err_msg['pass_re'])) echo 'err'; ?>">
              パスワード（再入力）
              <input type="password" name="pass_re" value="<?php if(!empty($_POST['pass_re'])) echo $_POST['pass_re']; ?>">
            </label>
            <div class="area-msg">
              <?php 
              if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re'];
              ?>
            </div>
            <div class="btn-container">
              <input type="submit" class="btn btn-mid" value="登録する">
            </div>
          </form>
        </div>

      </section>

    </div>

<?php
  require('footer.php');
?>
