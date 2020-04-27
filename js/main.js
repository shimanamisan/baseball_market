'use strict'; //厳密なエラーチェック

$(function(){
  // メッセージの表示
  var $jsShowMsg = $('#js-show-msg');
  // HTML出力されているメッセージの中身を取得
  var msg = $jsShowMsg.text();
  // HTML側で微妙に空白があるので、取り除いて文字が存在していると判定する
  // $_SESSION['success']にメッセージが詰め込まれていれば、メッセージが入っている
  if(msg.replace(/^[\s　]+|[\s　]+$/g, "").length){
    $jsShowMsg.slideToggle('slow');
    setTimeout(function(){
      $jsShowMsg.slideToggle('slow');
    }, 5000);
  }
  // フッターの固定
  var $ftr = $('#footer');
  console.log($ftr.offset());
  if( window.innerHeight > $ftr.offset().top + $ftr.outerHeight() ){
    $ftr.attr({'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;' });
  }
  //画像ライブプレビュー
  var $dropArea = $('.area-drop');
  var $fileInput = $('.input-file');
  var dragOverObject = {
    border: '3px #ccc dashed',
    transform: 'scale(1.05)',
    transition: 'all .25s'
  }
  var dragleaveObject = {
    border: 'none',
    width: '100%',
    transform: 'scale(1.0)',
    transition: 'all .25s'
  }
  $dropArea.on('dragover', function(e){
    // 親要素にイベントが伝播するのを防ぐ
    e.stopPropagation();
    // クリックイベントなどをキャンセルさせる
    // HTMLのリンクやチェックボックスによるイベントなどをキャンセルするメソッド
    e.preventDefault();
    // $(this).css(
    //   'border', '3px #ccc dashed'
    //   );
    $(this).css(dragOverObject);
  });
  $dropArea.on('dragleave', function(e){
    e.stopPropagation();
    e.preventDefault();
    // $(this).css('border', 'none');
    $(this).css(dragleaveObject);
  });
  $fileInput.on('change', function(e){
      // $dropArea.css('border', 'none');
      $dropArea.css(dragleaveObject);
      // 2.files配列にファイルが入っています、this($fileInput)はinputタグにつけられたクラスでファイル情報を取ってきている
      var file = this.files[0],
          // 3 .上記のthisに対して、$(this)としてjQueryオブジェクトにしている、そうすることでjQueryのsiblingsメソッドで兄弟（同列）のimgを取得
          $img = $(this).siblings('.prev-img'),
          // 4.ファイルを読み込むFileReaderオブジェクト
          fileReader = new FileReader();
          // console.log($img);

    // 5.onloadは読み込みが完了した際のイベントハンドラ、fileReaderにimgのsrcにデータをセットしている
    fileReader.onload = function(event){
      // 読み込んだデータをimgに設定
      $img.attr('src', event.target.result).show();
    };
    // 6.readAsDataURL()を使って、画像読み込み
    fileReader.readAsDataURL(file);
    console.log(fileReader);
  });
  // トップへ戻るボタン
  var top = $('.scroll-top');
  $(window).scroll(function () {
    // console.log($(this).scrollTop());
    if($(window).scrollTop() >= 300) {
        top.addClass('js-scroll');
    } else {
        top.removeClass('js-scroll'); 
    }
  });
  // テキストカウンター
  var $countUP = $('#js-count');
  var $countView = $('#js-count-view');
  var $counterOver = $('#js-textCount-Error');
  $countUP.on('keyup', function(){
    $countView.html($(this).val().length);
    if($(this).val().length > 500){
      $counterOver.html('500文字以内で入力してください。');
    }else{
      $counterOver.html('');
    }
  });
  // 画像切り替え
  var $switchImgSubs = $('.js-switch-img-sub'),
  $switchImgMain = $('#js-switch-img-main');
  $switchImgSubs.on('click', function(e){
    // 今回、switchImgSubsというものは3つあるので、どれをクリックしたのか判断できるように$(this)を指定している
    $switchImgMain.attr('src', $(this).attr('src'));
    //attr属性の値を取得してその値を返す
  });
  
  // scrollHeightは要素のスクロールビューの高さを取得するもの
  $('#js-scroll-bottom').animate({scrollTop: $('#js-scroll-bottom')[0].scrollHeight}, 'fast');
});

// 退会時の確認ダイアログ
function withdraw(){
  var checked = confirm('本当に退会しますか？');
  if(checked == true){
    return true;
  }else{
    return false;
  }
}

