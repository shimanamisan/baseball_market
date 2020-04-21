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

  var header = $('#header');
  $(window).scroll(function () {
        console.log($(this).scrollTop());
    if($(window).scrollTop() >= 60) {
        header.addClass('js-scroll');
        // sp_menu_wrap.addClass('js-scroll');
        // pc_menu_wrap.addClass('js-scroll');
    } else {
        header.removeClass('js-scroll'); 
        // sp_menu_wrap.removeClass('js-scroll'); 
        // pc_menu_wrap.removeClass('js-scroll'); 
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

