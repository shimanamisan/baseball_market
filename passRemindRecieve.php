<?php

// 共通関数を読み込み





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

          <form action="passEdit.html" class="form">
            <p>ご指定のメールアドレスお送りした【パスワード再発行認証メール】内にある「認証キー」をご入力ください。</p>
            <div class="area-msg">
              認証キーが違います
            </div>
            <label>
              認証キー
              <input type="text" name="token">
            </label>
            <div class="btn-container">
              <input type="submit" class="btn btn-mid" value="変更画面へ">
            </div>
          </form>
        </div>
        <a href="passRemindSend.html">&lt; パスワード再発行メールを再度送信する</a>
      </section>

    </div>

    <!-- footer -->
    <footer id="footer">
      Copyright <a href="http://webukatu.com/">ウェブカツ!!WEBサービス部</a>. All Rights Reserved.
    </footer>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>