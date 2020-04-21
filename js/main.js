$(function(){
  var $ftr = $('#footer');
  if( window.innerHeight > $ftr.offset().top + $ftr.outerHeight() ){
    $ftr.attr({'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;' });
  }
  });

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

function withdraw(){
  var checked = confirm('本当に退会しますか？');
  if(checked == true){
    return true;
  }else{
    return false;
  }
}