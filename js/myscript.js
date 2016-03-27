

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
    
    var heightSuccess = $('.success_content_main_text').height();
    $('.success_content_main_img').height(heightSuccess);
    
    
    $('#goods_filter_header').on('click', function(){
	   $('.goods_filter').slideToggle(500);
	})
    
    $('.ccc').on('click', function(){
	   $('#my_form').submit();
	})
    
    $('#desc').on('change', function(){
	   $('.goods_form_desc').submit();
	})
    
    //карусели в карточке товара
    
    $('#carousel1').carousel({
      interval: false
    });
    
    $('#carousel2').carousel({
      interval: false
    });
    
    $('#item').on('click', function(){
        var $b = $('html').height();
        $('#bg').css('display', 'block').css('height', $b);
        var $a = $('#item').html();
        $('.product_modal_contaner').css('display', 'flex');
        $('#show').css('display', 'flex').html('<div id="content_modal_close"><span class="glyphicon glyphicon-remove-sign"></span> Закрыть</div>'+$a).css('opacity', 0).delay(100).animate({
                        opacity: 1
                    }, 300);
        $("html,body").css("overflow","hidden"); 
        
    })
    
    $(document).on('click','#content_modal_close', function(){
        $('#bg').fadeOut(500);
        $('.product_modal_contaner').fadeOut(200);
        $("html,body").css("overflow","auto");
    });
    
    $('#bg').on('click', function(){
        $('#bg').fadeOut(500);
        $('.product_modal_contaner').fadeOut(200);
        $("html,body").css("overflow","auto");
        
    })
    
    $('.product_modal_contaner').height($(window).height());
        $(function(){
        $(window).resize(function() {
              $('.product_modal_contaner').height($(window).height());
        })
    })
    
    
                        
    
});


