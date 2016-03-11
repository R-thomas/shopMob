<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/cart.style.css" rel="stylesheet"/>

<div class="row success_wrap">
    <div class="success_content col-md-120 col-sm-120 col-xs-120">
        <div class="row">
            <div class="success_content_header col-md-120 col-sm-120 col-xs-120">
                <h1 class="text-uppercase text-center">Спасибо! Ваша заявка принята!</h1>
            </div>
        </div>
        <div class="row success_content_main_wrap">
            <div class="success_content_main col-md-120 col-sm-120 col-xs-120">
                <div class="row success_content_main_text">
                    <div class="col-md-35 col-sm-35 col-xs-35 success_content_main_img">
                        <div><img src="../../../images/tel_success.png" class="img-responsive" /></div>
                    </div>
                    <div class="col-md-85 col-sm-85 col-xs-85">
                        <div class="col-md-120 col-sm-120 col-xs-120 success_content_main_text_head">
                            <p class="text-uppercase">В ближайшее время мы с вами свяжемся!</p>
                        </div>
                        <div class="col-md-120 col-sm-120 col-xs-120 success_content_main_text_number">
                            <p>Ваш номер заказа: <span class="order_number"><?php echo $_GET['id_order']; ?></span></p>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>