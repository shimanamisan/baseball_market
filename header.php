<div id="wrap">
      <header id="header" class="header">
        <div class="site-width header">
          <h1><a href="index.php"><img src="img/logo/b-top_logo.png" alt="baseballitem"></a>
          <div class="sub-title"><span>野球用品専門のフリーマーケットサイトです！</span></div>
          </h1>
          <nav id="top-nav">
            <ul>
            <?php
              if(empty($_SESSION['user_id'])){
            ?>
              <li>
                <p class="list-contact">
                    <span class="list-contact-title">お問い合わせ</span>
                    <br>
                    <span class="list-contact-phone">
                      <i class="fa fa-phone list-contact-phone-icon"></i>
                      <span class="list-contact-phoneNumber">06-0980-9267</span>
                    </span>
                    <br>
                    <span class="list-contact-note">受付時間 9:00～18:00</span>
                </p>
              </li>
              <li><a href="signup.php" class="btn btn-primary u-radius"><i class="fas fa-user header-icon"></i>ユーザー登録</a></li>
              <li class="header-login"><a href="login.php"><i class="fas fa-sign-in-alt header-icon"></i>ログイン</a></li>

            <?php
              }else{
            ?>  
              <li>
                <p class="list-contact">
                    <span class="list-contact-title">お問い合わせ</span>
                    <br>
                    <span class="list-contact-phone">
                      <i class="fa fa-phone list-contact-phone-icon"></i>
                      <span class="list-contact-phoneNumber">06-0980-9267</span>
                    </span>
                    <br>
                    <span class="list-contact-note">受付時間 9:00～18:00</span>
                </p>
              </li>
              <li><a href="logout.php" class="btn btn-primary u-radius"><i class="fas fa-sign-in-alt header-icon"></i>ログアウト</a></li>
              <li class="header-login"><a href="mypage.php"><i class="fas fa-user header-icon"></i>マイページ</a></li>

            <?php
            }
            ?>

            </ul>
          </nav>
        </div>
      </header>
    </div>