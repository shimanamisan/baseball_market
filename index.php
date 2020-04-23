<?php

// 共通関数を読み込み
require('function.php');

debug('===============================');
debug('=== 商品一覧ページ index.php ===');
debug('===============================');
debugLogStart();

?>
<?php
  $siteTitle = 'HOME';
  require('head.php');
?>

  <body class="page-home page-2colum">

    <!-- ヘッダー -->
    <?php
      require('header.php');
    ?>

    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">

      <!-- サイドバー -->
      <section id="sidebar">
        <form>
          <h1 class="title">メーカー</h1>
          <div class="selectbox">
            <span class="icn_select"></span>
            <select class="category-form u-under-margin" name="category">
              <option value="1">ミズノ</option>
              <option value="2">ZETT</option>
              <option value="3">久保田スラッガー</option>
              <option value="4">UNDER ARMOUR</option>
              <option value="5">美津和タイガー</option>
              <option value="6">HATAKEYAMA</option>
            </select>
          </div>
          <h1 class="title">商品カテゴリー</h1>
          <div class="selectbox">
            <span class="icn_select"></span>
            <select class="category-form u-under-margin" name="category">
              <option value="1">グローブ</option>
              <option value="2">スパイク</option>
              <option value="3">バット</option>
              <option value="4">ユニフォーム</option>
              <option value="5">インナーウェア</option>
              <option value="6">その他道具</option>
            </select>
          </div>
          <h1 class="title">表示順</h1>
          <div class="selectbox">
            <span class="icn_select"></span>
            <select class="category-form" name="sort">
              <option value="1">金額が安い順</option>
              <option value="2">金額が高い順</option>
            </select>
          </div>
          <input class="submit-btn u-radius" type="submit" value="検索">
        </form>

      </section>

      <!-- Main -->
      <section id="main" >
        <div class="search-title">
          <div class="search-left">
            <span class="total-num num">104</span>件の商品が見つかりました
          </div>
          <div class="search-right">
            <span class="num">1</span> - <span class="num">40</span>件 / <span class="num">104</span>件中
          </div>
        </div>
        <div class="panel-list">
          <a href="productDetail.html" class="panel">
            <div class="panel-head">
              <img src="img/sample01.jpg" style="width:600; higeht:300;" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">iPhone6s <span class="price">¥89,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample02.jpg" width="600" higeht="300" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ASUS VivoBook E200HA <span class="price">¥75,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample06.jpg" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">MacBook Pro Retina <span class="price">¥89,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample04.jpg" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ミスノ　クロスバイク <span class="price">¥29,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample03.gif" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">電動自転車 <span class="price">¥58,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample08.jpg" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">アイアンセット <span class="price">¥12,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample07.jpg" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">フィットネスマシン <span class="price">¥34,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample10.jpg" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ウォーキングシューズ <span class="price">¥4,500</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample05.jpg" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">iPhone6s <span class="price">¥89,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample09.jpeg" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ASUS VivoBook E200HA <span class="price">¥75,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">MacBook Pro Retina <span class="price">¥89,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ミスノ　クロスバイク <span class="price">¥29,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">電動自転車 <span class="price">¥58,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">アイアンセット <span class="price">¥12,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">フィットネスマシン <span class="price">¥34,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ウォーキングシューズ <span class="price">¥4,500</span></p>
            </div>
          </a>
        </div>
        <div class="panel-list">
          <a href="productDetail.html" class="panel">
            <div class="panel-head">
              <img src="img/sample01.jpg" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">iPhone6s <span class="price">¥89,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample02.jpg" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ASUS VivoBook E200HA <span class="price">¥75,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample06.jpg" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">MacBook Pro Retina <span class="price">¥89,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample04.jpg" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ミスノ　クロスバイク <span class="price">¥29,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample03.gif" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">電動自転車 <span class="price">¥58,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample08.jpg" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">アイアンセット <span class="price">¥12,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample07.jpg" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">フィットネスマシン <span class="price">¥34,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample10.jpg" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ウォーキングシューズ <span class="price">¥4,500</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample05.jpg" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">iPhone6s <span class="price">¥89,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample09.jpeg" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ASUS VivoBook E200HA <span class="price">¥75,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">MacBook Pro Retina <span class="price">¥89,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ミスノ　クロスバイク <span class="price">¥29,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">電動自転車 <span class="price">¥58,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">アイアンセット <span class="price">¥12,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">フィットネスマシン <span class="price">¥34,000</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ウォーキングシューズ <span class="price">¥4,500</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ウォーキングシューズ <span class="price">¥4,500</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ウォーキングシューズ <span class="price">¥4,500</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ウォーキングシューズ <span class="price">¥4,500</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ウォーキングシューズ <span class="price">¥4,500</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ウォーキングシューズ <span class="price">¥4,500</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ウォーキングシューズ <span class="price">¥4,500</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ウォーキングシューズ <span class="price">¥4,500</span></p>
            </div>
          </a>
          <a href="" class="panel">
            <div class="panel-head">
              <img src="img/sample-img.png" alt="商品タイトル">
            </div>
            <div class="panel-body">
              <p class="panel-title">ウォーキングシューズ <span class="price">¥4,500</span></p>
            </div>
          </a>
        </div>

        <div class="pagination">
          <ul class="pagination-list">
            <li class="list-item"><a href="">&lt;</a></li>
            <li class="list-item"><a href="">1</a></li>
            <li class="list-item"><a href="">2</a></li>
            <li class="list-item active"><a href="">3</a></li>
            <li class="list-item"><a href="">4</a></li>
            <li class="list-item"><a href="">5</a></li>
            <li class="list-item"><a href="">&gt;</a></li>
          </ul>
        </div>
        
      </section>

    </div>

      <a href="#" class="scroll-top">TOP</a>
   

<?php
  require('footer.php');
?>
