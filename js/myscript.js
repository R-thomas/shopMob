

$(document).ready(function(){
    //Навигация
	$('.menu_ico').on('click', function(){
	   $('#header_nav_toggle').slideToggle(300);
	})


    //Фильтры
    if (window.screen.width > 767)
    {
        var $heightContent = $('#goods_content_goods').height();
        $('.goods_filter').height($heightContent-20);
    }
    
    $('#goods_filter_header').on('click', function(){
	   $('.goods_filter').slideToggle(500);
	})
});