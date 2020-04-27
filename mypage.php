<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('===============================');
debug('=== マイページ mypage.php ===');
debug('===============================');
debugLogStart();

//ログイン認証
require('auth.php');

//===============================
//画面表示用データ取得
//===============================
$u_id = $_SESSION['user_id'];
debug('SESSION[id]のユーザーIDを確認しています mypage.php：'.print_r($u_id,true));
// DBから商品データ取得
$productData = getMyProducts($u_id);
// DBから連絡掲示板データ取得
$bordData = getMyMsgsAndBord($u_id);
// DBからお気に入りデータを取得
$likeData = getMyLike($u_id);
// 販売者のユーザー情報を取得
if(!empty($bordData)){
    $buyUser = getUser($bordData[0]['sale_user']);
    debug('相手のユーザー情報です'.print_r($buyUser, true));
}



// DBからきちんと全てのデータが取れているかのチェックは行わず、取れなければ何も表示しないこととする
// debug('取得した掲示板データ mypage.php：'.print_r($productData, true));
// debug('取得した掲示板データ mypage.php：'.print_r($likeData, true));
// debug('取得した掲示板データ mypage.php：'.print_r($bordData, true));
// debug('相手のユーザー情報を取得 mypage.php：' .print_r($buyUser, true));

?>  

<?php
$siteTitle = 'マイページ';
require('head.php');
?>

 <body class="page-mypage page-2colum page-logined">

  <!-- ヘッダー -->
  <?php
  require('header.php');
  ?>

  <p id="js-show-msg" style="display:none; line-height:90px;" class="msg-slide">
      <?php echo getSessionFlash('msg_success');?> 
  </p>

        <!-- メインコンテンツ -->
        <div id="contents" class="site-width">
          
          <h1 class="page-title">MYPAGE</h1>

          <!-- Main -->
          <section id="main" >
            <section class="list panel-list">
              <h2 class="title">
                <span class="title-header">登録商品一覧</span>
              </h2>
                <?php
                    if(!empty($productData)):
                      foreach($productData as $key => $val):
                ?>
              <a href="registProduct.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&p_id='.$val['id'] : '?p_id='.$val['id']; ?>" class="panel">
                <div class="trim">
                  <div class="panel-head">
                    <img src="<?php echo showImg(sanitize($val['pic1'])); ?>" alt="<?php echo sanitize($val['name']); ?>">
                  </div>
                </div>
                <div class="panel-body">
                  <p class="panel-title"><?php echo sanitize($val['name']); ?> <span class="price">¥<?php echo sanitize(number_format($val['price'])); ?></span></p>
                </div>
              </a>
                <?php
                    endforeach;
                  endif;
                ?>
              </section>
                        
            <section class="list list-table">
              <h2 class="title">
                <span class="title-header">登録商品一覧連絡掲示板一覧</span>
              </h2>
              <table class="table">
                <thead>
                  <tr>
                    <th>最新送信日時</th>
                    <th>取引相手</th>
                    <th>メッセージ</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    if(!empty($bordData)){
                      foreach($bordData as $key => $val){
                        if(!empty($val['msg'])){
                          $msg = array_shift($val['msg']);
                          // debug('ボードデータです mypage.php：'.print_r($bordData,true));
                  ?>
                  <tr>
                      <td><?php echo sanitize(date('Y/m/d H:i', strtotime($msg['send_date']))); ?></td>
                      <td><?php echo sanitize($buyUser['username']);?></td>
                      <td><a href="msg.php?m_id=<?php echo sanitize($val['id']); ?>"><?php echo mb_substr(sanitize($msg['msg']),0,40); ?></a></td>
                  </tr>
                  <?php
                        }else{
                  ?>
                  <tr>
                    <td>-- --</td>
                    <td>○○ ○○</td>
                    <a href="msg.php?m_id=<?php echo sanitize($val['id']); ?>">まだメッセージはありません</a></td>
                  <?php
                      }
                    }
                  }
                  ?>
                  </tr>
                 </tbody>
              </table>
            </section>
            
            <section class="list panel-list">
              <h2 class="title">
                <span class="title-header">登録商品一覧お気に入り一覧</span>
              </h2>
              <?php
                if(!empty($likeData)):
                  foreach($likeData as $key => $val):
              ?>
              <a href="productDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&p_id='.$val['id'] : '?p_id='.$val['id'] ?>" class="panel">
                <div class="trim">
                  <div class="panel-head">
                    <img src="<?php echo showImg(sanitize($val['pic1'])); ?>" alt="<?php echo sanitize($val['name']); ?>">
                  </div>
                </div> 
                <div class="panel-body">
                <p class="panel-title"><?php echo sanitize($val['name']); ?> <span class="price">¥<?php echo sanitize(number_format($val['price'])); ?></span></p>
                </div>
              <?php
                endforeach;
              endif;
              ?>

            </section>
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
