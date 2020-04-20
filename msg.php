<?php

// 共通関数を読み込み


?>
<?php
  $siteTitle = '連絡掲示板';
  require('head.php');
?>

  <body class="page-msg page-1colum">

    <!-- ヘッダー -->
    <?php
      require('header.php');
    ?>

    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">
      <!-- Main -->
      <section id="main" >
        <div class="msg-info">
          <div class="avatar-img">
            <img src="img/avatar.png" alt="" class="avatar"><br>
          </div>
          <div class="avatar-info">
            山田　太郎　33歳<br>
            〒111-11111<br>
            東京都墨田区◯◯◯１−１−１◯◯◯マンション１３０３号室<br>
            TEL：000-0000-0000
          </div>
          <div class="product-info">
            <div class="left">
              取引商品<br>
              <img src="img/sample01.jpg" alt="" height="70px" width="auto" >
            </div>
            <div class="right">
              iPhone6s<br>
              取引金額：<span class="price">¥87,000</span><br>
              取引開始日：2016/00/00
            </div>
          </div>
        </div>
        <div class="area-bord" id="js-scroll-bottom">
          <div class="msg-cnt msg-left">
            <div class="avatar">
              <img src="img/avatar2.jpg" alt="" class="avatar">
            </div>
            <p class="msg-inrTxt">
              <span class="triangle"></span>
              サンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキスト
            </p>
          </div>
          <div class="msg-cnt msg-right">
            <div class="avatar">
              <img src="img/avatar.png" alt="" class="avatar">
            </div>
            <p class="msg-inrTxt">
              <span class="triangle"></span>
              サンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキ
            </p>
          </div>
          <div class="msg-cnt msg-left">
            <div class="avatar">
              <img src="img/avatar2.jpg" alt="" class="avatar">
            </div>
            <p class="msg-inrTxt">
              <span class="triangle"></span>
              サンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサ
            </p>
          </div>
          <div class="msg-cnt msg-right">
            <div class="avatar">
              <img src="img/avatar.png" alt="" class="avatar">
            </div>
            <p class="msg-inrTxt">
              <span class="triangle"></span>
              サンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキ
            </p>
          </div>
          <div class="msg-cnt msg-left">
            <div class="avatar">
              <img src="img/avatar2.jpg" alt="" class="avatar">
            </div>
            <p class="msg-inrTxt">
              <span class="triangle"></span>
              サンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサ
            </p>
          </div>
          <div class="msg-cnt msg-right">
            <div class="avatar">
              <img src="img/avatar.png" alt="" class="avatar">
            </div>
            <p class="msg-inrTxt">
              <span class="triangle"></span>
              サンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキ
            </p>
          </div>
          <div class="msg-cnt msg-left">
            <div class="avatar">
              <img src="img/avatar2.jpg" alt="" class="avatar">
            </div>
            <p class="msg-inrTxt">
              <span class="triangle"></span>
              サンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサンプルテキストサ
            </p>
          </div>
        </div>
        <div class="area-send-msg">
          <textarea name="" id="" cols="30" rows="3"></textarea>
          <input type="submit" value="送信" class="btn btn-send">
        </div>
      </section>
      
      <script src="js/vendor/jquery-2.2.2.min.js"></script>
      
      <script>
        $(function(){
          $('#js-scroll-bottom').animate({scrollTop: $('#js-scroll-bottom')[0].scrollHeight}, 'fast');
        });
      </script>

    </div>

    <!-- footer -->
    <footer>
      Copyright <a href="http://webukatu.com/">ウェブカツ!!WEBサービス部</a>. All Rights Reserved.
    </footer>

  </body>
</html>
