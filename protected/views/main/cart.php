<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/cart.style.css" rel="stylesheet"/>

<div class="row cart_wrap">
    <div class="col-md-120 col-sm-120 col-xs-120 cart_content">
        <div class="col-md-120 col-sm-120 col-xs-120 cart_content_header">
            <h1 class="text-uppercase">Корзина</h1>
        </div>
        <div class="col-md-120 col-sm-120 col-xs-120 cart_content_head">
            <div class="col-md-60 col-sm-120 col-xs-120 cart_content_head_text cart_content_head_text1">
                <p>Товар</p>
            </div>
            <div class="col-md-20 hidden-sm hidden-xs cart_content_head_text cart_content_head_text2">
                <p>&nbsp;&nbsp;&nbsp;&nbsp;Цена</p>
            </div>
            <div class="col-md-20 hidden-sm hidden-xs cart_content_head_text cart_content_head_text2">
                <p>&nbsp;&nbsp;Количество</p>
            </div>
            <div class="col-md-20 hidden-sm hidden-xs cart_content_head_text cart_content_head_text2">
                <p>Сумма&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
            </div>
        </div>
        <?php 
              $positions = Yii::app()->shoppingCart->getPositions();
              $cost = Yii::app()->shoppingCart->getCost();
                
              if($positions)
              {
                echo CHtml::form('', '', array('id'=>'my_form'));
                foreach($positions as $position)
                {
                echo '
                    <div class="col-md-120 col-sm-120 col-xs-120 cart_content_order">
                        <div class="col-md-120 col-sm-120 col-xs-120 cart_content_order_inner">
                            <div class="col-md-25 col-sm-35 col-xs-120 cart_content_order_inner1">
                                <div class="col-md-20 col-sm-20 col-xs-10 cart_content_order_remove">'
                                    .CHtml::checkBox('submit_cart', false, array('value'=>$position->id, 'id'=>$position->id, 'style'=>'display:none', 'class'=>'ccc')).
                                    CHtml::label('', $position->id, array('class'=>'glyphicon glyphicon-remove-sign'))  .'
                                </div>
                                <div class="col-md-100 col-sm-100 col-xs-110 cart_content_order_img">
                                    <img src="../../../upload/images/'.$position->photo.'"/>
                                </div>
                                
                            </div>
                            <div class="col-md-95 col-sm-85 col-xs-120 cart_content_order_inner2">
                                <div class="col-md-48 col-sm-120 col-xs-120 cart_content_order_name">
                                    <p>'.$position->brandModel->brand.' '.$position->model_name.'</p>
                                </div>
                                <div class="col-md-25 col-sm-120 col-xs-120 cart_content_order_val cart_content_order_val1">
                                    <span class="cart_order_title">Цена:&nbsp;</span><span class="cart_order_price_val">'.$position->getPrice().' p</span>
                                </div>
                                <div class="col-md-25 col-sm-120 col-xs-120 cart_content_order_val cart_content_order_val2">
                                    <span class="cart_order_title">Количество:&nbsp;</span><span class="order_minus">&nbsp;&nbsp;-&nbsp;&nbsp;</span><input type="text" value="'.$position->getQuantity().'" class="cart_order_input"/><span class="order_plus">&nbsp;&nbsp;+&nbsp;&nbsp;</span>
                                </div>
                                <div class="col-md-22 col-sm-120 col-xs-120 cart_content_order_val cart_content_order_val3">
                                    <span class="cart_order_title">Сумма:&nbsp;</span><span class="cart_order_price_val">'.$position->getSumPrice().' р</span>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                ';
                }
                echo CHtml::endForm();
              }
              else
              {
                echo '<p>Корзина пуста...</p>';
              }
              //echo CHtml::submitButton('отправить');
                 
        ?>
        
        
        <div class="col-md-120 col-sm-120 col-xs-120 cart_content_head cart_content_total">
            <p class="text-right">Итого: <span><?php echo $cost; ?> р&nbsp;</span>&nbsp;</p>
        </div>
        
        <div class="col-md-120 col-sm-120 col-xs-120 cart_an_order">
            <h2 class="text-uppercase text-center">Оформить заказ</h2>
        </div>
        
        <div class="col-md-120 col-sm-120 col-xs-120 cart_an_order">
            <div class="cart_order_form">
                <input type="text" placeholder="Имя..." class="cart_order_form_name"/><span class="asterisk">&nbsp;*</span>
                <input type="text" placeholder="Номер телефона..." class="cart_order_form_tel"/><span class="asterisk">&nbsp;*</span>
                <input type="text" placeholder="Email..." class="cart_order_form_email"/><span class="asterisk">&nbsp;*</span>
                <input type="submit" value="Оформить заказ" class="cart_order_form_submit"/>
            </div>
        </div>
    </div>
</div>