$(document).ready(function () {

  // TODO: add real toasts
  $('.toast').toast('show');


  if($('.flights').length){
    $('.flights').mCustomScrollbar({ theme: "minimal" });
  }


  $('.sidebar').draggable();
  $('.popup--theme').draggable();
});