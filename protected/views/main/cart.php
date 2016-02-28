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
                                    <span class="cart_order_title">Количество:&nbsp;</span>
                                    <span class="order_minus" id="minus'.$position->id.'">&nbsp;&nbsp;-&nbsp;&nbsp;</span>
                                    <input type="text" value="'.$position->getQuantity().'" class="cart_order_input" id="cart_order_input'.$position->id.'" disabled style="background-color: #fff;"/>
                                    <span class="order_plus" id="plus'.$position->id.'">&nbsp;&nbsp;+&nbsp;&nbsp;</span>
                                    <script>
                                        $(\'body\').on(\'click\',\'#minus'.$position->id.'\',function(){
                                        $.ajax({
                                            \'type\':\'POST\',
                                            \'dataType\':\'json\',
                                            \'success\':function(data){
                                                $("#count_update").text(data[1]);
                                                $("#sum_update, #cart_total").text(data[0]);
                                                $("#cart_order_input'.$position->id.'").val(data[2]);
                                                $("#cart_order_price_val'.$position->id.'").text(data[3]+\' р\');
                                                
                                                
                                            },
                                            \'url\':\'/main/cart\',
                                            \'cache\':false,
                                            \'data\': ({\'id\':'.$position->id.', 
                                                        \'button\':\'minus\'})})
                                        });
                                        
                                        $(\'body\').on(\'click\',\'#plus'.$position->id.'\',function(){
                                        $.ajax({
                                            \'type\':\'POST\',
                                            \'dataType\':\'json\',
                                            \'success\':function(data){
                                                $("#count_update").text(data[1]);
                                                $("#sum_update, #cart_total").text(data[0]);
                                                $("#cart_order_input'.$position->id.'").val(data[2]);
                                                $("#cart_order_price_val'.$position->id.'").text(data[3]+\' р\');
                                                
                                                
                                            },
                                            \'url\':\'/main/cart\',
                                            \'cache\':false,
                                            \'data\': ({\'id\':'.$position->id.', 
                                                        \'button\':\'plus\'})})
                                        });
                                    </script> 
                                </div>
                                <div class="col-md-22 col-sm-120 col-xs-120 cart_content_order_val cart_content_order_val3">
                                    <span class="cart_order_title">Сумма:&nbsp;</span><span class="cart_order_price_val" id="cart_order_price_val'.$position->id.'">'.$position->getSumPrice().' р</span>
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
            <p class="text-right">Итого: <span id="cart_total"><?php echo $cost; ?></span><span>&nbsp;р</span>&nbsp;</p>
        </div>
        
        <div class="col-md-120 col-sm-120 col-xs-120 cart_an_order">
            <h2 class="text-uppercase text-center">Оформить заказ</h2>
        </div>
        
        <div class="col-md-120 col-sm-120 col-xs-120 cart_an_order">
            <div class="cart_order_form">
            
                <?php $form=$this->beginWidget('CActiveForm', array(
                    	'id'=>'orders-form',
                    	// Please note: When you enable ajax validation, make sure the corresponding
                    	// controller action is handling ajax validation correctly.
                    	// There is a call to performAjaxValidation() commented in generated controller code.
                    	// See class documentation of CActiveForm for details on this.
                    	'enableAjaxValidation'=>false,
                    )); ?>

        		<?php echo $form->textField($order,'name',array('class'=>'cart_order_form_name', 'placeholder'=>'Имя...')); ?><span class="asterisk">&nbsp;*</span>
        		<?php echo $form->error($order,'name'); ?>
                <?php echo $form->textField($order,'tel',array('class'=>'cart_order_form_tel', 'placeholder'=>'Номер телефона...')); ?><span class="asterisk">&nbsp;*</span>
		        <?php echo $form->error($order,'tel'); ?>
                <?php echo $form->textField($order,'email',array('class'=>'cart_order_form_email', 'placeholder'=>'Email...')); ?><span class="asterisk">&nbsp;*</span>
		        <?php echo $form->error($order,'email'); ?>
                <?php echo CHtml::submitButton('Оформить заказ', array('class'=>'cart_order_form_submit', 'name'=>'order')); ?>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>