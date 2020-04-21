<?php

// 共通関数
require('function.php');

debug('===============================');
debug('=== プロフィール編集画面 profEdit.php ===');
debug('===============================');
debugLogStart();

// ログイン認証
require('auth.php');

// DBからユーザーデータを取得
$dbFormData = getUser($_SESSION['user_id']);

debug('取得したユーザー情報はこれです getUser関数 profEdit.php：' . print_r($dbFormData,true));

//POST送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報はこれです：' . print_r($_POST,true));
  debug('FILE情報：' . print_r($_FILES,true));

    // 変数にユーザー情報を代入
    $username = $_POST['username'];
    $tel = $_POST['tel'];
    // DBのzipカラムの方はint型だったので、値が空だとしても0が入る
    // 入力フォームで空だったら空文字が返ってくる。DBの0と入力フォームの空文字を比べてしまう
    // 後続のバリデーションに引っかかるため、空で送信されてきたら0を入れる
    $zip = (!empty($_POST['zip'])) ? $_POST['zip'] : 0;
    $addr = $_POST['addr'];
    $age = $_POST['age'];
    $email = $_POST['email'];

    // 画像をアップロードし、パスを格納
    $pic = (!empty($_FILES['pic']['name'])) ? uploadImg($_FILES['pic'], 'pic') : '';
    // 画像をPOSTしていない（登録していない）がすでにDBへ登録されている場合、DBのパスを入れる（POSTには反映されないので）
    $pic = (empty($pic) && !empty($dbFormData['pic'])) ? $dbFormData['pic'] : $pic ;

    //DBの情報と入力情報が異なる場合にバリデーションを行う
    if($dbFormData['username'] !== $username){
      //名前の最大文字数チェック
      validMaxLen($username, 'username');
    }
    if($dbFormData['tel'] !== $tel){
      //TELの形式チェック
      validTel($tel, 'tel');
    }
    if($dbFormData['addr'] !== $addr){
      //住所の最大文字数チェック
      validMaxLen($addr, 'addr');
    }
    if((int)$dbFormData['zip'] !== $zip){ //DBデータをint型にキャスト（型変換）して比較
      //DBから取得したデータは文字列として返ってくる
      //POSTが空だったら0が強制的に入るので（これは数値の0）、DBから返ってきた型の0（これは数字）と比較すると !==（型まで比較）なのでfalseになる
      //そのため(int)を使用してDBのデータをint型に変換して比較できるようにしている
      //郵便番号形式チェック
      validZip($zip, 'zip');
    }
    if($dbFormData['age'] !== $age){
      // 年齢の最大文字数チェック
      // validMaxLen($age, 'age');
      // 年齢の半角数字チェック
      validNumber($age, 'age');
    }
    if($dbFormData['email'] !== $email){
      //Emailの最大文字数チェック
      validMaxLen($email, 'email');

        // Lesson14の質問にて処理の負荷の観点からEmail未入力チェックのあとに持ってきたほうが良いかも？(19/06/29)
        if(empty($err_msg['email'])){
          // Emailの重複チェック
          validEmailDup($email);
        }

      //Emailの形式チェック
      validEmail($email, 'email');
      //Emailの未入力チェック
      validRequired($email, 'email');
    }

  if(empty($err_msg)){
    
    debug('****** バリデーションOKです ******');
    
    //例外処理
    try{
      //DBへ接続
      $dbh = dbConnect();
      //SQL文作成
      $sql = 'UPDATE users SET username = :u_name, age = :age, tel = :tel, zip = :zip, addr = :addr, email = :email, pic = :pic WHERE id = :u_id';
      $data = array(':u_name' => $username, ':age' => $age, ':tel' => $tel, ':zip' => $zip, ':addr' => $addr, ':email' => $email, ':pic' => $pic , ':u_id' => $dbFormData['id']);
      //クエリ実行
      $stmt = queryPost($dbh, $sql, $data);

      //クエリ成功の場合
      if($stmt){
        $_SESSION['msg_success'] = SUC02;
        debug('マイページへ遷移します');
        header("Location:mypage.php"); //マイページへ
      }

    }catch (Exception $e){
      error_log('エラー発生：' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}
debug('************');
debug('処理終了 profEdit.php');
debug('************');
?>

<?php
$siteTitle = 'プロフィール編集';
require('head.php');
?>

  <body class="page-profEdit page-2colum page-logined">

  <!-- ヘッダー -->
  <?php
  require('header.php');
  ?>

    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">
      <h1 class="page-title">プロフィール編集</h1>
      <!-- Main -->
      <section id="main" >
        <div class="form-container">
          <form action="" method="post" class="form prof-form-wrapp" enctype="multipart/form-data">
            <div class="area-msg">
              <?php
              if(!empty($err_msg['common'])) echo $err_msg['common'];
              ?>
            </div>

           <label class="<?php if(!empty($err_msg['username'])) echo 'err'; ?>">
             名前
             <input type="text" name="username" class="prof-form-width" value="<?php echo getFormData('username'); ?>">
           </label>
           <div class="area-msg">
              <?php
              if(!empty($err_msg['username'])) echo $err_msg['username'];
              ?>
            </div>

           <label class="<?php if(!empty($err_msg['tel'])) echo 'err'; ?>">
              TEL<span style="font-size:12px; margin-left:5px;">※ハイフンなしでご入力ください</span>
              <input type="text" name="tel" class="prof-form-width" value="<?php echo getFormData('tel');?>">
           </label>

           <div class="area-msg">
              <?php
              if(!empty($err_msg['tel'])) echo $err_msg['tel'];
              ?>
            </div>
           
            <label class="<?php if(!empty($err_msg['zip'])) echo 'err'; ?>">
              郵便番号<span style="font-size:12px; margin-left:5px;">※ハイフンなしでご入力ください</span>
              <input type="text" name="zip" value="<?php if(!empty(getFormData('zip')) ){echo getFormData('zip'); } ?>">
            </label>

            <div class="area-msg">
              <?php
              if(!empty($err_msg['zip'])) echo $err_msg['zip'];
              ?>
            </div>
           
            <label class="<?php if(!empty($err_msg['addr'])) echo 'err'; ?>">
              住所
              <input type="text" name="addr" class="prof-form-width" value="<?php echo getFormData('addr');?>">
            </label>

            <div class="area-msg">
              <?php
              if(!empty($err_msg['addr'])) echo $err_msg['addr'];
              ?>
            </div>

            <label style="text-align:left;" class="<?php if(!empty($err_msg['age'])) echo 'err'; ?>">
             年齢
              <input type="number" name="age" value="<?php echo getFormData('age');?>">
            </label>
            <!-- type="number" だと、ブラウザ側でエラーを出すからバリデーションがいらない？？(19/06/29) -->

           <div class="area-msg">
              <?php
              if(!empty($err_msg['age'])) echo $err_msg['age'];
              ?>
            </div><!-- area-msg -->

            <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
              Email
              <input type="text" name="email"  value="<?php echo getFormData('email'); ?>">
            </label>
  
              <div class="area-msg">
                <?php
                if(!empty($err_msg['email'])) echo $err_msg['email'];
                ?>
              </div><!-- area-msg -->

              プロフィール画像<!-- 画像登録用のHTMLタグ -->
              <label class="area-drop <?php if(empty($err_msg['pic'])) echo 'err'; ?>" style="height: 370px; line-height: 370px;">
                <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                <input type="file" name="pic" style="height: 370px;">
                <img src="<?php echo getFormData('pic');?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic'))) echo 'display: none;' ?>">
                ドラッグ＆ドロップ
              </label>
              <div class="area-msg">
                <?php
                if(!empty($err_msg['pic'])) echo $err_msg['pic'];
                ?>
              </div><!-- area-msg -->
                
            <div class="btn-container">
              <input type="submit" class="btn btn-mid" value="変更する">
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
