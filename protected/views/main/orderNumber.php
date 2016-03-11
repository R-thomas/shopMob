<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/cart.style.css" rel="stylesheet"/>

<div class="row success_wrap">
    <div class="success_content col-md-120 col-sm-120 col-xs-120">
        <div class="row">
            <div class="success_content_header col-md-120 col-sm-120 col-xs-120">
                <h1 class="text-uppercase text-center">Проверить статус заказа</h1>
            </div>
        </div>
        <div class="row success_content_main_wrap">
            <div class="success_content_main col-md-120 col-sm-120 col-xs-120">
                <div class="row success_content_main_text">
                    <div class="col-md-35 col-sm-30 col-xs-25 success_content_main_img">
                        <div><img src="../../../images/order_number.png" class="img-responsive" /></div>
                    </div>
                    <div class="col-md-60 col-sm-65 col-xs-65 order_number_text">
                        <div class="col-md-120 col-sm-120 col-xs-120 order_number_text_p">
                            <p class="text-uppercase">Введите номер своего заказа</p>
                        </div>
                        <div class="col-md-120 col-sm-120 col-xs-120 order_number_text_input">
                            <?php echo CHtml::form();
                        
                                  echo CHtml::textField('input', '', array('class'=>'order_input',
                                                                           'placeholder'=>'Номер заказа'));
                                  
                                  echo CHtml::ajaxSubmitButton('Проверить', '', array(
                                        'type' => 'POST',
                                        // Результат запроса записываем в элемент, найденный
                                        // по CSS-селектору #output.
                                        'update' => '#output',
                                    ),
                                    array(
                                        // Меняем тип элемента на submit, чтобы у пользователей
                                        // с отключенным JavaScript всё было хорошо.
                                        'type' => 'submit',
                                        'class' => 'order_submit'
                                    ));
                                     
                                  
                                  echo CHtml::endForm();
                                  
                            ?>      
                        </div>
                    </div>
                    <div class="col-md-25 col-sm-25 col-xs-30 order_status">
                        <div class="status"><p id="output" class="text-uppercase text-center"></p></div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>