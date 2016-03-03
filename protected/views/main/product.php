<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/product.style.css" rel="stylesheet"/>
<div class="product_modal_contaner"><div id="show"></div></div>
<div id="bg"></div>
<div class="row product_wrap">
    <div class="row product_contaner">
        <div class="row prod_contaner">
            <div class="col-md-120 col-sm-120 col-xs-120 product_header">
                <h1 class="text-uppercase"><?php echo ($model->categoryId->category_id == 1?'Мобильный телефон':($model->categoryId->category_id == 2?'Планшет':($model->categoryId->category_id == 3?'Ноутбук':''))).' '.$model->brandModel->brand.' '.$model->model_name ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-80 col-sm-120 col-xs-120 product_wr1">
                <div class="col-md-60 col-sm-60 col-xs-120 product_photo">
                    <div class="col-md-120 col-sm-120 col-xs-120 product_photo_main" id="item">
                        <?php echo '<img src="../../../upload/images/'.$model->photo.'" />' ?>
                    </div>
                    <div class="col-md-120 col-sm-120 col-xs-120 product_photo_slider">
                        <?php CommonHelper::getSliderThreeColumn($model) ?>
                    </div>
                </div>
                <div class="col-md-60 col-sm-60 col-xs-120 product_info">
                    <p class="text-uppercase product_info_presence"><?php echo $model->quantity>0?'В наличии':'Под заказ'; ?></p>
                    <p class="product_info_vendor_code">Артикул: <?php echo $model->vendor_code; ?></p>
                    <div>
                        <span class="product_info_price_title">Цена: </span><span class="product_info_price"><?php echo $model->price; ?> р&nbsp;&nbsp;&nbsp;</span><span class="product_info_old_price"><s><?php echo $model->old_price!=0?$model->old_price.' р':''; ?></s></span>
                    </div>
                    <?php echo CHtml::form();?>
                    <?php
                   	      echo CHtml::ajaxSubmitButton($model->quantity>0?'Купить':'Заказать', '', array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'success' => 'function(data){
                                                $("#count_update").text(data[1]);
                                                $("#sum_update").text(data[0]);
                                                $(".index_modal_dialog").css({"display": "block", "opacity": "0"}).delay(500).animate({opacity: 0.6}, 300).delay(1500).animate({opacity: 0}, 300).css({"display": "block"});
                                                $(\'.loader\').css({\'display\':\'none\'});
                                            }',
                            ),
                            array(
                                'type' => 'submit',
                                'class'=>'product_info_orderbutton', 
                                'name'=>'submit'
                            ));
                    ?>
                    <script>
                        $('.product_info_orderbutton').on('click', function(){
                            $('.loader').css({'display':'block'});
                        })
                    </script>
                    
                    <?php echo CHtml::endForm();?>
                    <div class="product_information product_information1">
                        <p>Работаем 7 дней в неделю</p>
                    </div>
                    <div class="product_information product_information2">
                        <p>Забери товар в нашем магазине</p>
                    </div>
                    <div class="product_information product_information3">
                        <p>Оплата наличными при получении</p>
                    </div>
                    <div class="product_stickers">
                        <?php echo $model->top!=0?'<div class="sticker1"><p>ТОП</p></div>':''; ?>
                        <?php echo $model->promotion!=0?'<div class="sticker2"><p>Акция</p></div>':''; ?>
                        <?php echo $model->old_price!=0?'<div class="sticker3"><p>Скидка</p></div>':''; ?>
                    </div>
                </div>
                <div class="hidden-lg hidden-md col-sm-120 col-xs-120 product_characteristics">
                    <div class="product_characteristics_header">
                        <p>Характеристики</p>
                    </div>
                    <div class="product_characteristics_content">
                    <?php
                        foreach($char as $item)
                        {
                            echo ($item['parent_id']==0 
                                  ?'<p class="product_characteristics_content_title text-uppercase">'.$item['characteristic_name'].'</p>' 
                                  :'<p>'.(isset($item['value'])?$item['characteristic_name']:'').(isset($item['value'])?': ':'').(isset($item['value'])?$item['value']:'').' '.(isset($item['value'])?$item['unit']:'').'</p>');
                        }
	                         
                    ?>
                    </div>
                    
                </div>
                <div class="col-md-120 col-sm-120 col-xs-120 product_description">
                    <?php if(count($slider)>0)
                    {
                        echo '
                            <div class="product_description_additionally">
                                <div class="product_description_additionally_header">
                                    <p>С этим товаром покупают</p>
                                </div>
                                <div class="product_description_additionally_slider">';
                    }
                    ?>
                    <?php CommonHelper::getSliderFourColumn($slider) ?>
                    <?php if(count($slider)>0)
                    {                
                        echo '</div>
                            </div>
                        ';
                    }
                    ?>
                    
                    
                    <?php echo $model->description!=''?'<div class="product_characteristics_text">
                        <p class="product_characteristics_text_title text-uppercase">Описание</p>
                        '.$model->description.'</div>':'';?>
                </div>
            </div>
            <div class="col-md-40 hidden-sm hidden-xs product_wr2">
                <div class="col-md-120 hidden-sm hidden-xs product_characteristics product_characteristics_md_lg">
                    <div class="product_characteristics_header">
                        <p>Характеристики</p>
                    </div>
                    <div class="product_characteristics_content">
                    <?php
                        foreach($char as $item)
                        {
                            echo ($item['parent_id']==0 
                                  ?'<p class="product_characteristics_content_title text-uppercase">'.$item['characteristic_name'].'</p>' 
                                  :'<p>'.(isset($item['value'])?$item['characteristic_name']:'').(isset($item['value'])?': ':'').(isset($item['value'])?$item['value']:'').' '.(isset($item['value'])?$item['unit']:'').'</p>');
                        }
	                         
                    ?>
                    </div>
                    
                </div>
            </div>
            
            
            
            
        </div>
    </div>
</div>
<div class="loader_bg"><img src="../../../images/KFLtA.png" class="loader" width="100"  /></div>
<style>

.loader{
    position: fixed;
    top: 45%;
    left: 45%;
    display: none;
    -webkit-animation: preloader 1.5s infinite linear;
    -moz-animation: preloader 1.5s infinite linear;
    -ms-animation: preloader 1.5s infinite linear;
    -o-animation: preloader 1.5s infinite linear;
    animation: preloader 1.5s infinite linear;
}

@-webkit-keyframes preloader {
    to { -webkit-transform: rotate(360deg); }
}

@-moz-keyframes preloader {
    to { -moz-transform: rotate(360deg); }
}

@-ms-keyframes preloader {
    to { -ms-transform: rotate(360deg); }
}

@-o-keyframes preloader {
    to { -o-transform: rotate(360deg); }
}

@keyframes preloader {
    to { transform: rotate(360deg); }
}
</style> 