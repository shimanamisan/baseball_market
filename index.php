<?php

// 共通変数・関数ファイルを読込み
require('function.php');

debug('===============================');
debug('=== 商品一覧ページ index.php ===');
debug('===============================');
debugLogStart();

//================================
// 画面処理
//================================
// 画面表示用データ取得
//================================
//GETパラメータを取得
//--------------------------------
// カレントページ
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1; // デフォルトは1ページめ
// カテゴリー
$category = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
// メーカー
$maker = (!empty($_GET['m_id'])) ? $_GET['m_id'] : '';
// ソート順
$sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';

// ここでGETパラメータの型を確認しています
debug('ここでGETパラメータの型を確認しています： ' .gettype($currentPageNum));
debug('    ');
debug('ここでGETパラメータの内容を確認しています： ' . print_r($_GET, true));
debug('    ');

// // パラメータに不正な値が入っているかチェック
if (!preg_match("/^[0-9]+$/", $currentPageNum)) {
    // パラメータが数字かチェックしている
    error_log('エラー発生:指定ページに不正な値が入りました： '.$currentPageNum);
    // 平仮名や英字といった数字以外だったらトップページへリダイレクト
    header("Location:index.php");
    exit();
}

//パラメータに不正な値が入っているかチェック
if (!is_int((int)$currentPageNum)) {
    // int型でなければエラーが発生するよにしている。(int)が外れていた(2019-07-17 22:58:44)
    error_log('エラー発生:指定ページに不正な値が入りました： '.$currentPageNum);
    // トップページへ
    header("Location:index.php");
    exit();
}

// 表示件数
$listSpan = 40;
// 現在の表示レコード先頭を算出
// 1ページ目なら(1-1)*20 = 0 、 2ページ目なら(2-1)*20 = 20
$currentMinNum = (($currentPageNum-1)*$listSpan);
// DBから商品データを取得
$dbProductData = getProductList($currentMinNum, $category, $maker, $sort);
// DBから商品データを取得できていないということは、商品数が0またはパラメータがおかしいと考えられるので
// ページングした結果の画面ではなくトップページに遷移させる
if (empty($dbProductData['data'])) {
    error_log('エラー発生:指定ページに不正な値が入りました:' .print_r($dbProductData, true));
    debug('    ');
    header("Location:index.php");
    exit();
}


// DBからカテゴリデータを取得
$dbCategoryData = getCategory();
// DBからメーカーデータを取得
$dbMakerData = getMaker();
debug('現在のページ：'.$currentPageNum);
debug('    ');
// debug('フォーム用DBデータ：'.print_r($dbFormData,true));
// debug('    ');
// debug('カテゴリデータ：'.print_r($dbCategoryData,true));
// debug('    ');

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
            <select class="category-form u-under-margin" name="m_id">
              <option value="0" <?php if (getFormData('m_id', true) == 0) {
        echo 'selected';
    } ?>>選択してください</option>
                <?php
                  foreach ($dbMakerData as $key => $val) {
                      ?>
              <option value="<?php echo $val['id'] ?>" <?php if (getFormData('m_id', true) == $val['id']) {
                          echo 'selected';
                      } ?> >
                <?php echo $val['name']; ?>
              </option>
                <?php
                  }
                ?>
            </select>
          </div>
          <h1 class="title">商品カテゴリー</h1>
          <div class="selectbox">
            <span class="icn_select"></span>
            <select class="category-form u-under-margin" name="c_id">
            <option value="0" <?php if (getFormData('m_id', true) == 0) {
                    echo 'selected';
                } ?>>選択してください</option>
                <?php
                  foreach ($dbCategoryData as $key => $val) {
                      ?>
              <option value="<?php echo $val['id'] ?>" <?php if (getFormData('c_id', true) == $val['id']) {
                          echo 'selected';
                      } ?> >
                <?php echo $val['name']; ?>
              </option>
                <?php
                  }
                ?>
            </select>
          </div>
          <h1 class="title">表示順</h1>
          <div class="selectbox">
            <span class="icn_select"></span>
            <select class="category-form u-under-margin" name="sort">
              <option value="0" <?php if (getFormData('sort', true) == 0) {
                    echo 'selected';
                } ?> >選択してください</option>
              <option value="1" <?php if (getFormData('sort', true) == 1) {
                    echo 'selected';
                } ?> >金額が安い順</option>
              <option value="2" <?php if (getFormData('sort', true) == 2) {
                    echo 'selected';
                } ?> >金額が高い順</option>
            </select>
          </div>
          <input class="btn post-btn u-radius" type="submit" value="検索">
        </form>

      </section>

      <!-- Main -->
      <section id="main" >
        <div class="search-title">
          <div class="search-left">
            <span class="total-num"><?php echo sanitize($dbProductData['total']); ?></span>件の商品が見つかりました
          </div>
          <div class="search-right">
            <!-- データベースのレコードとしては、10件あったとしたら0件目から9件目を表示していることになるのでプラス1を必ずする -->
            <span class="num"><?php echo (!empty($dbProductData['data'])) ? $currentMinNum+1 : 0; ?></span> - <span class="num"><?php echo $currentMinNum+count($dbProductData['data']); ?></span>件 / <span class="num"><?php echo sanitize($dbProductData['total']); ?></span>件中
          </div>
        </div>
        <div class="panel-list">
          <?php
            foreach ($dbProductData['data'] as $key => $val):
          ?>
            <a href="productDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&p_id='.$val['id'] : '?p_id='.$val['id']; ?>" class="panel">
              <div class="panel-head">
                <div class="trim">
                  <img class="obj-fit-img" src="<?php echo sanitize($val['pic1']); ?>" alt="<?php echo sanitize($val['name']); ?>">
                </div>
              </div>
              <div class="panel-body">
                <p class="panel-title">
                  <span class="panel-text"><?php echo sanitize($val['name']); ?></span>
                    <span class="price">¥<?php echo sanitize(number_format($val['price'])); ?></span>
                </p>
              </div>
            </a>

          <?php
            endforeach;
          ?>
        </div>

        <!-- ページネーション -->
        <?php pagination($currentPageNum, $dbProductData['total_page'], $maker, $category, $sort);?>

      </section>

    </div>

      <a href="#" class="scroll-top">TOP</a>
   

<?php
  require('footer.php');
?>
