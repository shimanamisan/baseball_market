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
      $dropArea.css('border', 'none');
      var file = this.files[0],  // 2.files配列にファイルが入っています
          $img = $(this).siblings('.prev-img'),  //3 .jQueryのsiblingsメソッドで兄弟のimgを取得
          fileReader = new FileReader();  // 4.ファイルを読み込むFileReaderオブジェクト
          console.log($img);
    // 5.読み込みが完了した際のイベントハンドラ、imgのsrcにデータをセット
    fileReader.onload = function(event){
      // 読み込んだデータをimgに設定
      $img.attr('src', event.target.result).show();
    };
    // 6.画像読み込み
    fileReader.readAsDataURL(file);
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

