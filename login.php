<?php
  $siteTitle = 'HOME';
  require('head.php');
?>

  <body class="page-login page-1colum">

    <!-- ヘッダー -->
    <?php
      require('header.php');
    ?>

    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">

      <!-- Main -->
      <section id="main" >

       <div class="form-container">
        
         <form action="mypage.html" class="form">
           <h2 class="title">ログイン</h2>
           <div class="area-msg">
             メールアドレスまたはパスワードが違います
           </div>
           <label>
            メールアドレス
             <input type="text" name="email">
           </label>
           <label>
             パスワード
             <input type="text" name="pass">
           </label>
           <label>
             <input type="checkbox" name="pass_save">次回ログインを省略する
           </label>
            <div class="btn-container">
              <input type="submit" class="btn btn-mid" value="ログイン">
            </div>
            パスワードを忘れた方は<a href="passRemindSend.html">コチラ</a>
         </form>
       </div>

      </section>

    </div>

<?php
  require('footer.php');
?>
