<?php

// DB設定用ファイル読み込み（githubにはアップしないファイル）
require('db_conf.php');

//================================
// ログ
//================================
//ログを取るか
ini_set('log_errors', 'on');
//ログの出力ファイルを指定
ini_set('error_log', 'logs/php.log');
// タイムゾーンをセット

//================================
// デバッグ
//================================
//デバッグフラグ
$debug_flg = true;

//デバッグログ関数
function debug($str)
{
    global $debug_flg;
    if (!empty($debug_flg)) {
        error_log($str);
    }
}

//================================
// セッション準備・セッション有効期限を延ばす
//================================
// セッションファイルの置き場を変更する（/var/tmp/以下に置くと30日は削除されない）
// session_save_path();
// ガーベージコレクションが削除するセッションの有効期限を設定（30日以上経っているものに対してだけ１００分の１の確率で削除）
ini_set('session.gc_maxlifetime', 60*60*24*30);
// ブラウザを閉じても削除されないようにクッキー自体の有効期限を延ばす
ini_set('session.cookie_lifetime', 60*60*24*30);
// セッションを使う
session_start();
// 現在のセッションIDを新しく生成したものと置き換える（なりすましのセキュリティ対策）
session_regenerate_id();

//==================================
// 画面表示処理開始ログ吐き出し関数
//==================================
function debugLogStart()
{
    debug('====== ここから function.php の読み込み処理開始 ======');
    debug('セッションIDです function.php：'. session_id());
    debug('セッション変数の中身です function.php：'. print_r($_SESSION, true));
    debug('現在日時タイムスタンプ function.php：'. time());
    if (!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])) {
        debug('ログイン期限日時タイムスタンプ function.php：'.($_SESSION['login_date'] + $_SESSION['login_limit']));
    }
}

//================================
// 定数
//================================
//エラーメッセージを定数に設定
define('MSG00', '半角数字のみご利用可能です'); //年齢入力欄のバリデーション用に追加(オリジナル)、Lesson17でMSG17として追加されていた
define('MSG01', '入力必須です');
define('MSG02', 'Emailの形式で入力してください');
define('MSG03', 'パスワード（再入力）が合っていません');
define('MSG04', '半角英数字のみご利用いただけます');
define('MSG05', '6文字以上で入力してください');
define('MSG06', '256文字以内で入力してください');
define('MSG07', 'エラーが発生しました。しばらく経ってからやり直してください。');
define('MSG08', 'そのEmailは既に登録されています');
define('MSG09', 'メールアドレスまたはパスワードが違います');
define('MSG10', '電話番号の形式が違います');
define('MSG11', '郵便番号の形式が違います');
define('MSG12', '古いパスワードが違います');
define('MSG13', '古いパスワードと同じです');
define('MSG14', '文字で入力してください');
define('MSG15', '正しくありません');
define('MSG16', '有効期限が切れています');
define('MSG17', '自分の出品した商品は購入できません');
define('SUC01', 'パスワードを変更しました');
define('SUC02', 'プロフィールを変更しました');
define('SUC03', 'メールを送信しました');
define('SUC04', '登録しました');
define('SUC05', '購入しました！相手と連絡を取りましょう！');

// エラーメッセージ格納用の配列
$err_msg = array();

// バリデーション関数（未入力チェック）
function validRequired($str, $key)
{
    // 金額フォームも考えると数値の0はOKにする、空文字はダメにする（emptyは0は空でないと判断する）
    if ($str === '') {
        // グローバル変数を指定
        global $err_msg;
        $err_msg[$key] = MSG01;
    }
}

// バリデーション関数（Email形式チェック）
function validEmail($str, $key)
{
    if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)) {
        global $err_msg;
        $err_msg[$key] = MSG02;
    }
}

function validEmailDup($email)
{
    global $err_msg;
    //例外処理
    try {
        // DBへ接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
        $data = array(':email' => $email);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        // クエリ結果の値を取得
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // array_shift関数は配列の先頭を取り出す関数です。クエリ結果は配列形式で入っているので、
        // array_shiftで1つ目だけ取り出して判定します
        debug('Email重複チェックの結果：'.print_r($result, true));
        if (!empty(array_shift($result))) {
            $err_msg['email'] = MSG08;
        }
    } catch (Exception $e) {
        error_log('エラー発生 validEmailDup():' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}

// バリデーション関数（同値チェック）
function validMatch($str1, $str2, $key)
{
    if ($str1 !== $str2) {
        global $err_msg;
        $err_msg[$key] = MSG03;
    }
}

// 最小文字数チェック
function validMinLen($str, $key, $min = 6)
{
    if (mb_strlen($str) < $min) {
        global $err_msg;
        $err_msg[$key] = MSG05;
    }
}

//最大文字数チェック
function validMaxLen($str, $key, $max = 255)
{
    if (mb_strlen($str) > $max) {
        //mb_strlen：文字数を取得するための関数
        global $err_msg;
        $err_msg[$key] = MSG06;
    }
}

//半角チェック
function validHalf($str, $key)
{
    if (!preg_match("/^[a-zA-Z0-9]+$/", $str)) {
        global $err_msg;
        $err_msg[$key] = MSG04;
    }
}

//電話番号形式チェック
function validTel($str, $key)
{
    if (!preg_match("/0\d{1,4}\d{1,4}\d{4}/", $str)) {
        global $err_msg;
        $err_msg[$key] = MSG10;
    }
}

//郵便番号形式チェック
function validZip($str, $key)
{
    if (!preg_match("/^\d{7}$/", $str)) {
        global $err_msg;
        $err_msg[$key] = MSG11;
    }
}

//半角数字チェック
function validNumber($str, $key)
{
    if (!preg_match("/^[0-9]+$/", $str)) {
        global $err_msg;
        $err_msg[$key] = MSG00;
    }
}

//固定長チェック
function validLength($str, $key, $len = 8)
{
    if (mb_strlen($str) !== $len) {
        global $err_msg;
        $err_msg[$key] = $len . MSG14;
    }
}

// パスワードチェックまとめ
function validPass($str, $key)
{
    //半角英数チェック
    validHalf($str, $key);
    //最大文字数チェック
    validMaxLen($str, $key);
    //最小文字数チェック
    validMinLen($str, $key);
}

//セレクトボックスのチェック
function validSelect($str, $key)
{
    if (!preg_match("/^[1-9]?[0-9]+$/", $str)) {
        global $err_msg;
        $err_msg[$key] = MSG15;
    }
}

//エラーメッセージ表示
function getErrMsg($key)
{
    global $err_msg;
    if (!empty($err_msg[$key])) {
        return $err_msg[$key];
    }
}

//================================
// ログイン認証
//================================
// 関数の頭をisとしていたらtrueかfalseで返ってくる、とわかりやすくするための命名規則
// auth.phpではログイン有効期限が切れていたりしたらログインページに飛ばしていたが、お気に入り機能では
// それは必要ないのでDBに登録せずにtrue か falseを返すだけの処理にしている
function isLogin()
{
    // ログインしている場合
    if (!empty($_SESSION['login_date'])) {
        debug('ログイン済みユーザーです isLogin関数');
        debug('    ');
        // 現在時刻が最終ログイン日時+有効期限を超えていた場合
        if (($_SESSION['login_date'] + $_SESSION['login_limit']) < time()) {
            debug('ログイン有効期限オーバーです isLogin関数');
            debug('    ');

            // セッションを削除する（ログアウトする）
            session_destroy();
            return false;
        } else {
            debug('有効期限以内です isLogin関数');
            debug('    ');
            return true;
        }
    } else {
        debug('未ログインユーザーです isLogin関数');
        debug('    ');
        return false;
    }
}

//================================
// データベース
//================================
// DB接続関数
function dbConnect()
{
    // DBへの接続準備
    $dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';charset=utf8';
    $user = DB_USER;
    $password = DB_PASS;
    $options = array(
    // SQL実行失敗時にはエラーコードのみ設定
    // SQL実行時に例外を発生しないようにしている。emailをINSERTするときに関係してくる
    // ユニークにしたカラムで重複したものを登録した場合やDB接続エラーの場合、例外処理などのエラー処理をしていないと
    // エラー内容がユーザーにモロに見えてしまい、見た目もセキュリティ面でも良くない
    PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
    // デフォルトフェッチモードを連想配列形式に設定(https://www.php.net/manual/ja/pdostatement.fetch.php)
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
    // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
    // rowCountメソッドは PDOStatement オブジェクトによって実行された 直近の DELETE, INSERT, UPDATE 文によって作用した行数を返します。
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
  );
    // PDOオブジェクト生成（DBへ接続）
    $dbh = new PDO($dsn, $user, $password, $options);

    // PDOオブジェクトを返却しクエリー生成時などで使用する
    return $dbh;
}

function queryPost($dbh, $sql, $data)
{
    // クエリー作成：$dbhにPDOオブジェクトが入ってくるので、アロー演算子でオブジェクト内の関数を呼び出している
    $stmt = $dbh->prepare($sql);
    // プレースホルダに値をセットし、SQL文を実行
    // SQLの実行結果はtrue,falseで返ってくる
    if (!$stmt->execute($data)) {
        //PDOStatement::execute：プリペアドステートメントを実行する
        debug('クエリ失敗しました queryPost関数 function.php');
        debug('失敗したSQL queryPost関数 function.php：'.print_r($stmt, true));
        debug('DBハンドラエラーコード queryPost関数 function.php：'.print_r($stmt->errorInfo(), true));
        $err_msg['common'] = MSG07;
        return false;
    }
    debug('成功したSQL queryPost関数 function.php：'. $sql);
    return $stmt;
}

function getUser($u_id)
{
    debug('getUser関数 function.php');
    // 例外処理
    try {
        // DBへ接続します
        $dbh = dbConnect();
        // SQL文作成
    $sql = 'SELECT * FROM users WHERE id = :u_id AND delete_flg = 0'; // delete_flgがついていないユーザーを取ってくる
    $data = array(':u_id' => $u_id);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        // クエリ結果のデータを1レコード返却
        if ($stmt) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生(getUser)：' . $e->getMessage());
    }
}

function getProduct($u_id, $p_id)
{
    debug('商品情報を取得します getProduct関数');
    debug('ユーザーID getProduct関数 ：'.$u_id);
    debug('商品ID getProduct関数 ：'.$p_id);
    // 例外処理
    try {
        // DBへ接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT * FROM product WHERE user_id = :u_id AND id = :p_id AND delete_flg = 0';
        $data = array(':u_id' => $u_id, ':p_id' => $p_id);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        if ($stmt) {
            // クエリ結果のデータを1レコード返却
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
    }
}

function getProductList($currentMinNum = 1, $category, $maker, $sort, $span = 40)
{
    debug('商品情報を取得します。getProductList関数');
    //例外処理
    try {
        // DBへ接続
        $dbh = dbConnect();
      
        // 件数用のSQL文作成
        if (!empty($category) && !empty($maker)) {
            $sql = 'SELECT id FROM product WHERE category_id = ' .$category. ' AND maker_id = '. $maker;
        } elseif (!empty($category)) {
            $sql = 'SELECT id FROM product WHERE category_id = ' .$category;
        } elseif (!empty($maker)) {
            $sql = 'SELECT id FROM product WHERE maker_id = ' .$maker;
        } else {
            $sql = 'SELECT id FROM product';
        }
        if (!empty($sort)) {
            switch ($sort) {
            case 1:
              $sql .= ' ORDER BY price ASC';
              break;
            case 2:
              $sql .= ' ORDER BY price DESC';
              break;
        }
        }
        $data = array();
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        // 総レコード数
        $rst['total'] = $stmt->rowCount();
        // 総ページ数
        $rst['total_page'] = ceil($rst['total']/$span);
        if (!$stmt) {
            return false;
        }
        // ページング用のSQL文作成
        // $sql = 'SELECT * FROM product';
        if (!empty($category) && !empty($maker)) {
            debug('カテゴリーとメーカーの両方で検索された時のSQL getProductList');
            $sql = 'SELECT * FROM product WHERE category_id = ' .$category. ' AND maker_id = '. $maker;
        } elseif (!empty($category)) {
            debug('カテゴリーのみで検索された時のSQL getProductList');
            $sql = 'SELECT * FROM product WHERE category_id = ' .$category;
        } elseif (!empty($maker)) {
            debug('メーカーのみで検索された時のSQL getProductList');
            $sql = 'SELECT * FROM product WHERE maker_id = ' .$maker;
        } else {
            // 新しく登録された商品から表示する
            // $sql = 'SELECT * FROM product ORDER BY create_date DESC';
            $sql = 'SELECT * FROM product';
            debug('何も選択されてない時のSQL getProductList');
        }
        if (!empty($sort)) {
            switch ($sort) {
                case 1:
                  $sql .= ' ORDER BY price ASC';
                  break;
                case 2:
                  $sql .= ' ORDER BY price DESC';
                  break;
            }
        }
        // プレースホルダーを使わずに変数に検索用の値を代入しているやりかた
        // $sql .= ' LIMIT '.$span.' OFFSET '.$currentMinNum;
        // 変数を結合してSQLを作成するときもプレースホルダーにバインドするやり方
        $sql .= ' LIMIT :span OFFSET :currentMinNum';
        $stmt = $dbh->prepare($sql);
        debug('セットされているSQL：'. $sql);
        $stmt->bindValue(':span', $span, PDO::PARAM_INT);
        $stmt->bindValue(':currentMinNum', $currentMinNum, PDO::PARAM_INT);
        //クエリ実行
        $stmt->execute();
        debug('DBハンドラエラーコード getProductList関数 function.php：'.print_r($stmt->errorInfo(), true));

        if ($stmt) {
            // クエリ結果の全レコードを格納
            // 二次元配列形式 = 配列の中にさらに配列形式で入っている
            $rst['data'] = $stmt->fetchAll();
            // debug('クエリ結果のレコードの中身です getProductList関数：'.print_r($rst,true));
            return $rst;
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生 getProductList関数：' . $e->getMessage());
    }
}

function getProductOne($p_id)
{
    debug('商品情報を取得します getProductOne関数', print_r($p_id, true));
    debug('商品ID getProductOne関数：'.$p_id);
    //例外処理
    try {
        // DBへ接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT p.id, p.name,
                    p.comment,
                    p.price,
                    p.pic1,
                    p.pic2,
                    p.pic3,
                    p.user_id,
                    p.create_date,
                    p.update_date,
                    c.name AS category /* categoryという名前で取得できるように別名にしている。c.nameのままだと、nameという名前で取得されるので、PHP内で側で何のカラムなのか分かりづらい */
                    FROM product AS p LEFT JOIN category AS c ON p.category_id = c.id
                    WHERE p.id = :p_id AND p.delete_flg = 0 AND c.delete_flg = 0';
        // AS categoryとすることで別名をつけれる。c.とはcategoryテーブルのこと。
        $data = array(':p_id' => $p_id);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        if ($stmt) {
            // クエリ結果のデータを１レコード返却
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生 getProductOne関数:' . $e->getMessage());
    }
}

function getMyProducts($u_id)
{
    debug('自分の商品情報を取得します getMyproducts関数');
    debug('ユーザーID getMyproducts関数：'.$u_id);
    //例外処理
    try {
        $dbh = dbConnect();
        //SQL文作成
        $sql = 'SELECT * FROM product WHERE user_id = :u_id AND delete_flg = 0';
        $data = array(':u_id' => $u_id);
        //SQL実行
        $stmt = queryPost($dbh, $sql, $data);

        if ($stmt) {
            //クエリ結果のデータを全レコード返却
        return $stmt->fetchAll(); //連想配列形式で返却される
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生：'.$e->getMessage());
    }
}

function getMsgsAndBord($id)
{
    debug('掲示板情報を取得 getMsgsAndBord関数：' .$id);
    //例外処理
    try {
        //DBへ接続
        $dbh = dbConnect();
        //SQL文作成
        $sql = 'SELECT b.id, m.id AS m_id, 
            bord_id, product_id, send_date, to_user, from_user,
            msg, b.delete_flg, b.create_date, b.sale_user, b.buy_user 
            FROM message AS m 
            RIGHT JOIN bord AS b 
            ON b.id = m.bord_id 
            WHERE b.id = :id 
            ORDER BY send_date ASC';
        $data = array(':id' => $id);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        if ($stmt) {
            //クエリ結果の全データを返却
            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生：'.$e->getMessage());
    }
}

function getMyMsgsAndBord($u_id)
{
    debug('自分のmsg情報を取得します getMyMsgsAndBord関数');
    //例外処理
    try {
        //DBへ接続
        $dbh = dbConnect();
        //まず、自分の掲示板のデータを全部取得
        //SQL文作成
        $sql = 'SELECT * FROM bord AS b WHERE b.sale_user = :id OR b.buy_user = :id AND b.delete_flg = 0';
        $data = array(':id' => $u_id);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        $rst = $stmt->fetchAll();
        // debug('掲示板レコードが空でないか確認 getMyMsgsAndBord関数：'.print_r($rst,true));
        if (!empty($rst)) {
            // 掲示板のデータが有った場合にはforeachで配列を展開する
            foreach ($rst as $key => $val) {
                debug('$key：'.print_r($key, true));
                debug('$val：'.print_r($val, true));
                // SQL文作成
                // $rstの結果からボードテーブルから掲示板IDを引っ張ってきているので、その掲示板IDをもとにメッセージを取得
                $sql = 'SELECT * FROM message WHERE bord_id = :id AND delete_flg = 0 ORDER BY send_date DESC';
                $data = array(':id' => $val['id']);
                // クエリ実行
                $stmt = queryPost($dbh, $sql, $data);
                // msgというキーを作ってその中に取得したメッセージ情報を格納する
                $rst[$key]['msg'] = $stmt->fetchAll();
                debug('メッセージ格納後の配列 $rst：' .print_r($rst, true));
            }
        }
      
        if ($stmt) {
            //クエリ結果の全データを返却
            return $rst;
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生：'.$e->getMessage());
    }
}

function getCategory()
{
    debug('カテゴリー情報を取得します。：getCategory関数');
    //例外処理
    try {
        // DBへ接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT * FROM category';
        $data = array();
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        if ($stmt) {
            // クエリ結果の全データを返却
            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生。getCategory：' . $e->getMessage());
    }
}

function getMaker()
{
    debug('メーカー情報を取得します。：getMaker関数');
    // 例外処理
    try {
        // DBへ接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT * FROM maker';
        $data = array();
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        if ($stmt) {
            // クエリ結果の全データを返却
            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生。getMaker関数：' . $e->getMessage());
    }
}

function isLike($u_id, $p_id)
{
    debug('お気に入り情報がある確認します isLike関数');
    debug('    ');
    debug('ユーザーID isLike関数：'.$u_id);
    debug('商品ID isLike関数：'.$p_id);
    debug('    ');
    //例外処理
    try {
        //DBへ接続
        $dbh = dbConnect();
        //SQL文作成
        $sql = 'SELECT * FROM `like` WHERE product_id = :p_id AND user_id = :u_id';
        $data = array(':u_id' => $u_id, ':p_id' => $p_id);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        if ($stmt->rowCount()) {
            debug('お気に入りです isLike関数');
            debug('    ');
            return true;
        } else {
            debug('お気に入りではありません isLike関数');
            debug('    ');
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生：'. $e->getMessage());
    }
}

function getMyLike($u_id)
{
    debug('自分のお気に入り情報を取得します getMyLike関数');
    debug('ユーザーID getMyLike関数：'.$u_id);
    //例外処理
    try {
        //DBへ接続
        $dbh = dbConnect();
        //SQL文作成
        $sql = 'SELECT * FROM `like` AS l LEFT JOIN product AS p ON l.product_id = p.id WHERE l.user_id = :u_id';
        $data = array(':u_id' => $u_id);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        if ($stmt) {
            //クエリの全データを返却
            return $stmt->fetchAll();
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生：'.$e->getMessage());
    }
}

//================================
// メール送信
//================================
function sendMail($from, $to, $subject, $comment)
{
    if (!empty($to) && !empty($subject) && !empty($comment)) {
        //文字化けしないように設定（お決まりパターン）
        mb_language("Japanese"); //現在使っている言語を設定する
        mb_internal_encoding("UTF-8"); //内部の日本語をどうエンコーディング（機械が分かる言葉へ変換）するかを設定
        
        //メールを送信（送信結果はtrueかfalseで返ってくる）
        $result = mb_send_mail($to, $subject, $comment, "From: ".$from);
        //送信結果を判定
        if ($result) {
            debug('メールを送信しました。');
        } else {
            debug('【エラー発生】メールの送信に失敗しました。');
        }
    }
}

//================================
// その他
//================================
// サニタイズ
function sanitize($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

// フォーム入力保持
function getFormData($str, $flg = false)
{
    if ($flg) {
        $method = $_GET;
    } else {
        $method = $_POST;
    }
    global $dbFormData;
    // ユーザーデータがある場合
    if (!empty($dbFormData)) {
        //フォームのエラーがある場合
        if (!empty($err_msg[$str])) {
            //POSTにデータがある場合
            if (isset($method[$str])) {
                return sanitize($method[$str]);
            } else {
                //ない場合（基本ありえない）はDBの情報を表示
                return sanitize($dbFormData[$str]);
            }
        } else {
            //POSTにデータがあり、DBの情報と違う場合
            if (isset($method[$str]) && $method[$str] !== $dbFormData[$str]) {
                return sanitize($method[$str]);
            } else {
                return sanitize($dbFormData[$str]);
            }
        }
    } else {
        if (isset($method[$str])) {
            return sanitize($method[$str]);
        }
    }
}

// sessionを１回だけ取得できる
function getSessionFlash($key)
{
    if (!empty($_SESSION[$key])) {
        $data = $_SESSION[$key];
        debug('セッションに入るメッセージ getSessionFlash関数 function.php ：'.($data));
        $_SESSION[$key] = ''; //ここでセッションの$keyの1つが空になる
        return $data;
    }
}

// 認証キー生成
function makeRandKey($length = 8)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
    $str = '';
    for ($i = 0; $i < $length; ++$i) {
        $str .= $chars[mt_rand(0, 61)];
    }
    return $str;
}

// 画像処理
function uploadImg($file, $key)
{
    debug('画像アップロード処理開始');
    debug('FILE情報：'.print_r($file, true));
  
    // エラーの中になにか入っていて且つ数値であれば画像が入っていると判定
    if (isset($file['error']) && is_int($file['error'])) {
        try {
            // バリデーション
            // $file['error'] の値を確認。配列内には「UPLOAD_ERR_OK」などの定数が入っている。
            //「UPLOAD_ERR_OK」などの定数はphpでファイルアップロード時に自動的に定義される。定数には値として0や1などの数値が入っている。
            switch ($file['error']) {
          case UPLOAD_ERR_OK: // OK
              break;
          case UPLOAD_ERR_NO_FILE:   // ファイル未選択の場合
              throw new RuntimeException('ファイルが選択されていません');
          case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズが超過した場合
          case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過した場合
              throw new RuntimeException('ファイルサイズが大きすぎます');
          default: // その他の場合
              throw new RuntimeException('その他のエラーが発生しました');
      }
      
            // $file['mime']の値はブラウザ側で偽装可能なので、MIMEタイプを自前でチェックする
            // exif_imagetype関数は「IMAGETYPE_GIF」「IMAGETYPE_JPEG」などの定数を返す
            // @を先頭につけることで、エラーが発生しても無視する
            $type = @exif_imagetype($file['tmp_name']);
            // 画像の形式を判定している
      if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) { // 第三引数にはtrueを設定すると厳密にチェックしてくれるので必ずつける
          throw new RuntimeException('画像形式が未対応です');
      }
            // ファイルデータからSHA-1ハッシュを取ってファイル名を決定し、ファイルを保存する
            // ハッシュ化しておかないとアップロードされたファイル名そのままで保存してしまうと同じファイル名がアップロードされる可能性があり、
            // DBにパスを保存した場合、どっちの画像のパスなのか判断つかなくなってしまう
            // image_type_to_extension関数はファイルの拡張子を取得するもの
            $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
            if (!move_uploaded_file($file['tmp_name'], $path)) { //ファイルを移動する
                throw new RuntimeException('ファイル保存時にエラーが発生しました');
            }
            // 保存したファイルパスのパーミッション（権限）を変更する
            chmod($path, 0644);
      
            debug('ファイルは正常にアップロードされました');
            debug('ファイルパス：'.$path);
            return $path;
        } catch (RuntimeException $e) {
            debug($e->getMessage());
            global $err_msg;
            $err_msg[$key] = $e->getMessage();
        }
    }
}
//ページング
// $currentPageNum : 現在のページ数
// $totalPageNum : 総ページ数
// $link : 検索用GETパラメータリンク
// $pageColNum : ページネーション表示数
function pagination($currentPageNum, $totalPageNum, $maker = '', $category = '', $sort = '', $pageColNum = 5)
{
    // 現在のページが、総ページ数と同じ かつ 総ページ数が表示項目数以上なら、左にリンク4個出す
    if ($currentPageNum == $totalPageNum && $totalPageNum >= $pageColNum) {
        debug('現在のページが、総ページ数と同じ かつ 総ページ数が表示項目数以上の処理');
        $minPageNum = $currentPageNum - 4;
        $maxPageNum = $currentPageNum;
    // 現在のページが、総ページ数の1ページ前なら、左にリンク3個、右に1個出す
    } elseif ($currentPageNum == ($totalPageNum-1) && $totalPageNum >= $pageColNum) {
        debug('現在のページが、総ページ数の1ページ前の処理');
        $minPageNum = $currentPageNum - 3;
        $maxPageNum = $currentPageNum + 1;
    // 現ページが2の場合は左にリンク1個、右にリンク3個だす。
    } elseif ($currentPageNum == 2 && $totalPageNum >= $pageColNum) {
        debug('現ページが2の処理');
        $minPageNum = $currentPageNum - 1;
        $maxPageNum = $currentPageNum + 3;
    // 現ページが1の場合は左に何も出さない。右に5個出す。
    } elseif ($currentPageNum == 1 && $totalPageNum >= $pageColNum) {
        debug('現ページが1の処理');
        $minPageNum = $currentPageNum;
        $maxPageNum = 5;
    // 総ページ数が表示項目数より少ない場合は、総ページ数をループのMax、ループのMinを1に設定
    } elseif ($totalPageNum < $pageColNum) {
        debug('総ページ数が表示項目数より少ない場合の処理');
        $minPageNum = 1;
        $maxPageNum = $totalPageNum;
    // それ以外は左に２個出す。
    } else {
        debug('elseのページネーション処理');
        $minPageNum = $currentPageNum - 2;
        $maxPageNum = $currentPageNum + 2;
    }

    // 検索時のGETパラメータをページネーション用URLでも表示するようにする
    if (!empty($maker) && !empty($category) && !empty($sort)) {
        $link = '&m_id='.$maker.'&c_id='.$category.'&sort='.$sort;
        debug('カテゴリナンバーがすべて入っている時の処理'. print_r($_GET, true));
    } elseif (!empty($maker) && !empty($category)) {
        $link = '&m_id='.$maker.'&c_id='.$category.'&sort=0';
        debug('m_idとc_idが入っているとき');
    } elseif (!empty($maker) && !empty($sort)) {
        $link = '&m_id='.$maker.'&c_id=0&sort='.$sort;
        debug('m_idとsortが入っているとき');
    } elseif (!empty($category) && !empty($sort)) {
        $link = '&m_id=0&c_id='. $category .'&sort='.$sort;
        debug('c_idとsortが入っているとき');
        debug('    ');
    } elseif (!empty($maker)) {
        $link = '&m_id='.$maker.'&c_id=0&sort=0';
        debug('m_idのみが入っているとき');
        debug('    ');
    } elseif (!empty($category)) {
        $link = '&m_id=0&c_id='.$category.'&sort=0';
        debug('c_idのみが入っているとき');
        debug('    ');
    } elseif (!empty($sort)) {
        $link = '&m_id=0&c_id=0&sort='.$sort;
        debug('sortのみが入っているとき');
        debug('    ');
    } elseif (!empty($_GET['c_id']) && !empty($_GET['sort'])) {
        $link = '&m_id=0&c_id='.$category.'&sort=0';
    } else {
        debug('カテゴリナンバーが何も入っていない時の処理'. print_r($_GET, true));
        debug('    ');
        $link = '';
    }

    //PHPのタグの中ではHTMLは書けないので、そういった場合は echo で出力すると良い
    echo '<div class="pagination">';
    echo '<ul class="pagination-list">';
    if ($currentPageNum != 1) {
        echo '<li class="list-item"><a href="?p=1'.$link.'">&lt;</a></li>';
    }
    for ($i = $minPageNum; $i <= $maxPageNum; $i++) {
        echo '<li class="list-item ';
        if ($currentPageNum == $i) {
            echo 'active';
        }
        echo '"><a href="?p='.$i.$link.'">'.$i.'</a></li>';
    }
    if ($currentPageNum != $maxPageNum && $maxPageNum > 1) {
        echo '<li class="list-item"><a href="?p='.$maxPageNum.$link.'">&gt;</a></li>';
    }
    echo '</ul>';
    echo '</div>';
}
// 画像表示用関数
function showImg($path)
{
    if (empty($path)) {
        return 'img/sample-img.png';
    } else {
        return $path;
    }
}

// GETパラメータ付与
// $del_key : 付与から取り除きたいGETパラメータのキー
// productDetail.php?p=3&p_id=88となっているp_id=88の部分を取り除き、ページのパラメータ（p=3）だけ取り出す関数になっている
function appendGetParam($arr_del_key = array())
{
    // debug('appendGetParam関数 $arr_del_key：' . print_r($arr_del_key, true));
    // GETパラメータが取得されているか
    if (!empty($_GET)) {
        // GETパラメータの最初につく?を付与している
        $str = '?';
        foreach ($_GET as $key => $val) {
            // 取り除きたいパラメータじゃない場合にurlにくっつけるパラメータを生成
            debug('appendGetParam関数 foreachで展開しています');
            debug('appendGetParam関数 $_GET：' . print_r($_GET, true));
            debug('appendGetParam関数 $key：' . $key);
            debug('appendGetParam関数 $val：' . $val);
            debug('    ');
            if (!in_array($key, $arr_del_key, true)) {
                // keyがappendGetParamの引数に合致していなければGETパラメータとしてくっつける
                $str .= $key.'='.$val.'&';
                debug('appendGetParam関数 $srt：'. $str);
                debug('    ');
            }
        }
        $str = mb_substr($str, 0, -1, "UTF-8");
        // 最後につく&を取り除いている
        debug('appendGetParam関数 &を取り除いて返却：'. $str);
        debug('    ');
        return $str;
    }
}
