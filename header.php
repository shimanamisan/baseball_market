<div id="wrap">
      <header id="header" class="header">
        <div class="site-width header">
          <h1><a href="index.php"><img src="img/logo/b-top_logo.png" alt=""></a>
          <div class="sub-title"><span>野球用品専門のフリーマーケットサイトです！</span></div>
          </h1>
          <nav id="top-nav">
            <ul>
            <?php
              if(empty($_SESSION['user_id'])){
            ?>

              <li><a href="signup.php" class="btn btn-primary u-radius"><i class="fas fa-user header-icon"></i>ユーザー登録</a></li>
              <li class="header-login"><a href="login.php"><i class="fas fa-sign-in-alt header-icon"></i>ログイン</a></li>

            <?php
              }else{
            ?>  
            
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