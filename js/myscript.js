

$(document).ready(function(){
    //Навигация
	$('.menu_ico').on('click', function(){
	   $('#header_nav_toggle').slideToggle(300);
	})


    
    if (window.screen.width > 767)
    {
        //Фильтры - высота столбца
        if($('.goods_filter').height() < $('#goods_content_goods').height())
        {
            var $heightContent = $('#goods_content_goods').height();
            $('.goods_filter').height($heightContent-20);
        }
        
        // характеристики - высота столбца (продукт)
        if($('.product_characteristics_md_lg').height() < $('.product_wr1').height())
        {
            var $heightContent = $('.product_wr1').height();
            $('.product_characteristics_md_lg').height($heightContent);
        }
        
    }
    
    $('#goods_filter_header').on('click', function(){
	   $('.goods_filter').slideToggle(500);
	})
    
    $('.ccc').on('click', function(){
	   $('#my_form').submit();
	})
    
    
});