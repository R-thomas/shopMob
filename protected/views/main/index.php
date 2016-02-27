<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/index.style.css" rel="stylesheet"/>
<div class="row content_index">
    <div class="col-md-120 hidden-sm hidden-xs content_slider">
        <div id="carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <!--<div class="item active">
                    <img src="../../../images/slider_img1.jpg" width="960" height="282" />
                </div>
                <div class="item">
                    <img src="../../../images/slider_img1.jpg" width="960" height="282" />
                </div>
                <div class="item">
                    <img src="../../../images/slider_img1.jpg" width="960" height="282" />
                </div>
                -->
                <?php foreach ($banner as $k=>$img)
                      {
                        if($k == 0)
                        {
                            echo '<div class="item active index_banner">
                                    <img src="../../../upload/images/'.$img->img.'"/>
                                  </div>';
                        }
                        else
                        {
                            echo '<div class="item index_banner">
                                    <img src="../../../upload/images/'.$img->img.'"/>
                                  </div>';
                        }
                        
                        
                      }  
                    
                ?>
            </div>
            <!--Стрелки влево и вправо-->
            <a href="#carousel" class="left carousel-control" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a href="#carousel" class="right carousel-control" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
        </div>
    </div>
</div>
<div class="row">  
    <div class="content_index_menu col-md-120 hidden-sm hidden-xs">
        <div class="header_nav">
            <ul class="menu">
                <li class="nav_items"><a href="#">Работаем 7 дней в неделю</a></li>
                <li class="nav_items"><a href="#">Проверить статус заказа</a></li>
                <li class="nav_items"><a href="#">Товар под заказ</a></li>
                <li class="nav_items"><a href="#">Забери товар в ближайшем магазине</a></li>
            </ul>
        </div>
    </div>    
</div>
<?php echo CHtml::form();?>
<div class="row content_index_popular">
    <div class="content_index_popular_container">
        <div class="row">
            <div class="col-md-120 col-sm-120 col-xs-120 content_index_popular_header">
                <p class="text-uppercase">Популярные товары</p>
            </div>
        </div>
        <div class="row content_index_popular_cont"><!--
            <div class="col-md-40 col-sm-60 col-xs-120 content_first_item">
                <div class="content_index_img"><img src="../../../images/tel1.jpg" /></div>
                <div class="content_description"><a href="#">Телефоны></a>
                    <div class="content_tel_title"><a href="#">Samsug galaxy A 50000</a></div>    
                </div>
                <p class="content_old_price"><s>25000 р</s></p>
                <p class="content_price">19000 р</p>
                <div class="content_button_buy">Купить</div>   
            </div>
            <div class="col-md-40 col-sm-60 col-xs-120 content_second_item">
                <div class="content_index_img"><img src="../../../images/tel2.jpg" /></div>
                <div class="content_description"><a href="#">Телефоны></a>
                    <div class="content_tel_title"><a href="#">Samsug galaxy A 50000</a></div>    
                </div>
                <p class="content_old_price"><s>25000 р</s></p>
                <p class="content_price">19000 р</p>
                <div class="content_button_buy">Купить</div> 
            </div>
            <div class="col-md-40 col-sm-60 col-xs-120 content_third_item">
                <div class="content_index_img"><img src="../../../images/tel3.jpg" /></div>
                <div class="content_description"><a href="#">Телефоны></a>
                    <div class="content_tel_title"><a href="#">Samsug galaxy A 50000</a></div>    
                </div>
                <p class="content_old_price"><s></s></p>
                <p class="content_price">19000 р</p>
                <div class="content_button_buy">Купить</div> 
            </div>
            <div class="col-md-40 col-sm-60 col-xs-120 content_fourth_item">
                <div class="content_index_img"><img src="../../../images/tel4.jpg" /></div>
                <div class="content_description"><a href="#">Телефоны></a>
                    <div class="content_tel_title"><a href="#">Samsug galaxy A 50000</a></div>    
                </div>
                <p class="content_old_price"><s>25000 р</s></p>
                <p class="content_price">19000 р</p>
                <div class="content_button_buy">Купить</div> 
            </div>
            -->
            
            <?php 
                foreach ($topSales as $j=>$topGoods)
                {
                    if ($j == 0)
                    {
                        echo '<div class="col-md-40 col-sm-60 col-xs-120 content_first_item">
                                <div class="content_index_img"><img src="../../../upload/images/'.$topGoods->photo.'" /></div>
                                <div class="content_description"><a href="#">'.$topGoods->categoryId->category->category_name.'></a>
                                    <div class="content_tel_title"><a href="#">'.$topGoods->brandModel->brand
                                                                                .' '.$topGoods->model_name
                                                                                .'</a></div>    
                                </div>
                                <p class="content_old_price"><s>'.($topGoods->old_price != 0?$topGoods->old_price.' р':"").'</s></p>
                                <p class="content_price">'.$topGoods->price.' р</p>
                                <div class="content_button_buy" id="submit'.$topGoods->id.'">'.($topGoods->quantity>0?'Купить':'Заказать').'</div>
                                ';
                                                           
                                 
                        echo '</div>
                        <script>
                            $(\'body\').on(\'click\',\'#submit'.$topGoods->id.'\',function(){
                            $.ajax({
                                \'type\':\'POST\',
                                \'dataType\':\'json\',
                                \'success\':function(data){
                                    $("#count_update").text(data[1]);
                                    $("#sum_update").text(data[0]);
                                    $(\'.index_modal_dialog\').css({\'display\': \'block\', \'opacity\': \'0\'}).delay(500).animate({opacity: 0.6}, 300).delay(1500).animate({opacity: 0}, 300).css({\'display\': \'block\'});
                                    
                                },
                                \'url\':\'/main/index2\',
                                \'cache\':false,
                                \'data\': ({\'id\':'.$topGoods->id.'})})
                        });
                        </script>

                        ';
                    }
                    
                    else if ($j == 1)
                    {
                        echo '<div class="col-md-40 col-sm-60 col-xs-120 content_second_item">
                                <div class="content_index_img"><img src="../../../upload/images/'.$topGoods->photo.'" /></div>
                                <div class="content_description"><a href="#">'.$topGoods->categoryId->category->category_name.'></a>
                                    <div class="content_tel_title"><a href="#">'.$topGoods->brandModel->brand
                                                                                .' '.$topGoods->model_name
                                                                                .'</a></div>       
                                </div>
                                <p class="content_old_price"><s>'.($topGoods->old_price != 0?$topGoods->old_price.' р':"").'</s></p>
                                <p class="content_price">'.$topGoods->price.' р</p>
                                <div class="content_button_buy" id="submit'.$topGoods->id.'">'.($topGoods->quantity>0?'Купить':'Заказать').'</div>  
                            </div>
                            <script>
                                $(\'body\').on(\'click\',\'#submit'.$topGoods->id.'\',function(){
                                $.ajax({
                                    \'type\':\'POST\',
                                    \'dataType\':\'json\',
                                    \'success\':function(data){
                                        $("#count_update").text(data[1]);
                                        $("#sum_update").text(data[0]);
                                        $(\'.index_modal_dialog\').css({\'display\': \'block\', \'opacity\': \'0\'}).delay(500).animate({opacity: 0.6}, 300).delay(1500).animate({opacity: 0}, 300).css({\'display\': \'block\'});
                                    },
                                    \'url\':\'/main/index2\',
                                    \'cache\':false,
                                    \'data\': ({\'id\':'.$topGoods->id.'})})
                            });
                            </script>
                            ';
                    }
                    
                    else if ($j == 2)
                    {
                        echo '<div class="col-md-40 col-sm-60 col-xs-120 content_third_item">
                                <div class="content_index_img"><img src="../../../upload/images/'.$topGoods->photo.'" /></div>
                                <div class="content_description"><a href="#">'.$topGoods->categoryId->category->category_name.'></a>
                                    <div class="content_tel_title"><a href="#">'.$topGoods->brandModel->brand
                                                                                .' '.$topGoods->model_name
                                                                                .'</a></div>       
                                </div>
                                <p class="content_old_price"><s>'.($topGoods->old_price != 0?$topGoods->old_price.' р':"").'</s></p>
                                <p class="content_price">'.$topGoods->price.' р</p>
                                <div class="content_button_buy" id="submit'.$topGoods->id.'">'.($topGoods->quantity>0?'Купить':'Заказать').'</div>   
                            </div>
                            <script>
                                $(\'body\').on(\'click\',\'#submit'.$topGoods->id.'\',function(){
                                $.ajax({
                                    \'type\':\'POST\',
                                    \'dataType\':\'json\',
                                    \'success\':function(data){
                                        $("#count_update").text(data[1]);
                                        $("#sum_update").text(data[0]);
                                        $(\'.index_modal_dialog\').css({\'display\': \'block\', \'opacity\': \'0\'}).delay(500).animate({opacity: 0.6}, 300).delay(1500).animate({opacity: 0}, 300).css({\'display\': \'block\'});
                                    },
                                    \'url\':\'/main/index2\',
                                    \'cache\':false,
                                    \'data\': ({\'id\':'.$topGoods->id.'})})
                            });
                            </script>
                            ';
                    }
                    
                    else if ($j == 3)
                    {
                        echo '<div class="col-md-40 col-sm-60 col-xs-120 content_fourth_item">
                                <div class="content_index_img"><img src="../../../upload/images/'.$topGoods->photo.'" /></div>
                                <div class="content_description"><a href="#">'.$topGoods->categoryId->category->category_name.'></a>
                                    <div class="content_tel_title"><a href="#">'.$topGoods->brandModel->brand
                                                                                .' '.$topGoods->model_name
                                                                                .'</a></div>       
                                </div>
                                <p class="content_old_price"><s>'.($topGoods->old_price != 0?$topGoods->old_price.' р':"").'</s></p>
                                <p class="content_price">'.$topGoods->price.' р</p>
                                <div class="content_button_buy" id="submit'.$topGoods->id.'">'.($topGoods->quantity>0?'Купить':'Заказать').'</div>   
                            </div>
                            <script>
                                $(\'body\').on(\'click\',\'#submit'.$topGoods->id.'\',function(){
                                $.ajax({
                                    \'type\':\'POST\',
                                    \'dataType\':\'json\',
                                    \'success\':function(data){
                                        $("#count_update").text(data[1]);
                                        $("#sum_update").text(data[0]);
                                        $(\'.index_modal_dialog\').css({\'display\': \'block\', \'opacity\': \'0\'}).delay(500).animate({opacity: 0.6}, 300).delay(1500).animate({opacity: 0}, 300).css({\'display\': \'block\'});
                                    },
                                    \'url\':\'/main/index2\',
                                    \'cache\':false,
                                    \'data\': ({\'id\':'.$topGoods->id.'})})
                            });
                            </script>
                            ';
                    }
                    
                    else
                    {
                        break;
                    }
                    
                }
            ?>
        </div>
    </div>
</div>  

<div class="row content_index_popular">
    <div class="content_index_popular_container">
        <div class="row">
            <div class="col-md-120 col-sm-120 col-xs-120 content_index_popular_header">
                <p class="text-uppercase">Новинки</p>
            </div>
        </div>
        <div class="row content_index_popular_cont">
            <?php 
                foreach ($novelty as $j=>$topGoods)
                {
                    if ($j == 0)
                    {
                        echo '<div class="col-md-40 col-sm-60 col-xs-120 content_first_item">
                                <div class="content_index_img"><img src="../../../upload/images/'.$topGoods->photo.'" /></div>
                                <div class="content_description"><a href="#">'.$topGoods->categoryId->category->category_name.'></a>
                                    <div class="content_tel_title"><a href="#">'.$topGoods->brandModel->brand
                                                                                .' '.$topGoods->model_name
                                                                                .'</a></div>    
                                </div>
                                <p class="content_old_price"><s>'.($topGoods->old_price != 0?$topGoods->old_price.' р':"").'</s></p>
                                <p class="content_price">'.$topGoods->price.' р</p>
                                <div class="content_button_buy" id="submit_new'.$topGoods->id.'">'.($topGoods->quantity>0?'Купить':'Заказать').'</div>   
                            </div>
                            <script>
                                $(\'body\').on(\'click\',\'#submit_new'.$topGoods->id.'\',function(){
                                $.ajax({
                                    \'type\':\'POST\',
                                    \'dataType\':\'json\',
                                    \'success\':function(data){
                                        $("#count_update").text(data[1]);
                                        $("#sum_update").text(data[0]);
                                        $(\'.index_modal_dialog\').css({\'display\': \'block\', \'opacity\': \'0\'}).delay(500).animate({opacity: 0.6}, 300).delay(1500).animate({opacity: 0}, 300).css({\'display\': \'block\'});
                                    },
                                    \'url\':\'/main/index2\',
                                    \'cache\':false,
                                    \'data\': ({\'id\':'.$topGoods->id.'})})
                            });
                            </script>
                            ';
                    }
                    
                    else if ($j == 1)
                    {
                        echo '<div class="col-md-40 col-sm-60 col-xs-120 content_second_item">
                                <div class="content_index_img"><img src="../../../upload/images/'.$topGoods->photo.'" /></div>
                                <div class="content_description"><a href="#">'.$topGoods->categoryId->category->category_name.'></a>
                                    <div class="content_tel_title"><a href="#">'.$topGoods->brandModel->brand
                                                                                .' '.$topGoods->model_name
                                                                                .'</a></div>       
                                </div>
                                <p class="content_old_price"><s>'.($topGoods->old_price != 0?$topGoods->old_price.' р':"").'</s></p>
                                <p class="content_price">'.$topGoods->price.' р</p>
                                <div class="content_button_buy" id="submit_new'.$topGoods->id.'">'.($topGoods->quantity>0?'Купить':'Заказать').'</div>   
                            </div>
                            <script>
                                $(\'body\').on(\'click\',\'#submit_new'.$topGoods->id.'\',function(){
                                $.ajax({
                                    \'type\':\'POST\',
                                    \'dataType\':\'json\',
                                    \'success\':function(data){
                                        $("#count_update").text(data[1]);
                                        $("#sum_update").text(data[0]);
                                        $(\'.index_modal_dialog\').css({\'display\': \'block\', \'opacity\': \'0\'}).delay(500).animate({opacity: 0.6}, 300).delay(1500).animate({opacity: 0}, 300).css({\'display\': \'block\'});
                                    },
                                    \'url\':\'/main/index2\',
                                    \'cache\':false,
                                    \'data\': ({\'id\':'.$topGoods->id.'})})
                            });
                            </script>
                            ';
                    }
                    
                    else if ($j == 2)
                    {
                        echo '<div class="col-md-40 col-sm-60 col-xs-120 content_third_item">
                                <div class="content_index_img"><img src="../../../upload/images/'.$topGoods->photo.'" /></div>
                                <div class="content_description"><a href="#">'.$topGoods->categoryId->category->category_name.'></a>
                                    <div class="content_tel_title"><a href="#">'.$topGoods->brandModel->brand
                                                                                .' '.$topGoods->model_name
                                                                                .'</a></div>       
                                </div>
                                <p class="content_old_price"><s>'.($topGoods->old_price != 0?$topGoods->old_price.' р':"").'</s></p>
                                <p class="content_price">'.$topGoods->price.' р</p>
                                <div class="content_button_buy" id="submit_new'.$topGoods->id.'">'.($topGoods->quantity>0?'Купить':'Заказать').'</div>  
                            </div>
                            <script>
                                $(\'body\').on(\'click\',\'#submit_new'.$topGoods->id.'\',function(){
                                $.ajax({
                                    \'type\':\'POST\',
                                    \'dataType\':\'json\',
                                    \'success\':function(data){
                                        $("#count_update").text(data[1]);
                                        $("#sum_update").text(data[0]);
                                        $(\'.index_modal_dialog\').css({\'display\': \'block\', \'opacity\': \'0\'}).delay(500).animate({opacity: 0.6}, 300).delay(1500).animate({opacity: 0}, 300).css({\'display\': \'block\'});
                                    },
                                    \'url\':\'/main/index2\',
                                    \'cache\':false,
                                    \'data\': ({\'id\':'.$topGoods->id.'})})
                            });
                            </script>
                            ';
                    }
                    
                    else if ($j == 3)
                    {
                        echo '<div class="col-md-40 col-sm-60 col-xs-120 content_fourth_item">
                                <div class="content_index_img"><img src="../../../upload/images/'.$topGoods->photo.'" /></div>
                                <div class="content_description"><a href="#">'.$topGoods->categoryId->category->category_name.'></a>
                                    <div class="content_tel_title"><a href="#">'.$topGoods->brandModel->brand
                                                                                .' '.$topGoods->model_name
                                                                                .'</a></div>       
                                </div>
                                <p class="content_old_price"><s>'.($topGoods->old_price != 0?$topGoods->old_price.' р':"").'</s></p>
                                <p class="content_price">'.$topGoods->price.' р</p>
                                <div class="content_button_buy" id="submit_new'.$topGoods->id.'">'.($topGoods->quantity>0?'Купить':'Заказать').'</div>   
                            </div>
                            <script>
                                $(\'body\').on(\'click\',\'#submit_new'.$topGoods->id.'\',function(){
                                $.ajax({
                                    \'type\':\'POST\',
                                    \'dataType\':\'json\',
                                    \'success\':function(data){
                                        $("#count_update").text(data[1]);
                                        $("#sum_update").text(data[0]);
                                        $(\'.index_modal_dialog\').css({\'display\': \'block\', \'opacity\': \'0\'}).delay(500).animate({opacity: 0.6}, 300).delay(1500).animate({opacity: 0}, 300).css({\'display\': \'block\'});
                                    },
                                    \'url\':\'/main/index2\',
                                    \'cache\':false,
                                    \'data\': ({\'id\':'.$topGoods->id.'})})
                            });
                            </script>
                            ';
                    }
                    
                    else
                    {
                        break;
                    }
                    
                }
            ?>
        </div>
    </div>
</div>  

<div class="row content_index_popular">
    <div class="content_index_popular_container">
        <div class="row">
            <div class="col-md-120 col-sm-120 col-xs-120 content_index_popular_header">
                <p class="text-uppercase">Случайный товар</p>
            </div>
        </div>
        <div class="row content_index_popular_cont"><!--
            <div class="col-md-40 col-sm-60 col-xs-120 content_first_item">
                <div class="content_index_img"><img src="../../../images/tel1.jpg" /></div>
                <div class="content_description"><a href="#">Телефоны></a>
                    <div class="content_tel_title"><a href="#">Samsug galaxy A 50000</a></div>    
                </div>
                <p class="content_old_price"><s>25000 р</s></p>
                <p class="content_price">19000 р</p>
                <div class="content_button_buy">Купить</div>   
            </div>
            <div class="col-md-40 col-sm-60 col-xs-120 content_second_item">
                <div class="content_index_img"><img src="../../../images/tel2.jpg" /></div>
                <div class="content_description"><a href="#">Телефоны></a>
                    <div class="content_tel_title"><a href="#">Samsug galaxy A 50000</a></div>    
                </div>
                <p class="content_old_price"><s>25000 р</s></p>
                <p class="content_price">19000 р</p>
                <div class="content_button_buy">Купить</div> 
            </div>
            <div class="col-md-40 col-sm-60 col-xs-120 content_third_item">
                <div class="content_index_img"><img src="../../../images/tel3.jpg" /></div>
                <div class="content_description"><a href="#">Телефоны></a>
                    <div class="content_tel_title"><a href="#">Samsug galaxy A 50000</a></div>    
                </div>
                <p class="content_old_price"><s></s></p>
                <p class="content_price">19000 р</p>
                <div class="content_button_buy">Купить</div> 
            </div>
            <div class="col-md-40 col-sm-60 col-xs-120 content_fourth_item">
                <div class="content_index_img"><img src="../../../images/tel4.jpg" /></div>
                <div class="content_description"><a href="#">Телефоны></a>
                    <div class="content_tel_title"><a href="#">Samsug galaxy A 50000</a></div>    
                </div>
                <p class="content_old_price"><s>25000 р</s></p>
                <p class="content_price">19000 р</p>
                <div class="content_button_buy">Купить</div> 
            </div>-->
            
            <?php 
                foreach ($random as $j=>$topGoods)
                {
                    if ($j == 0)
                    {
                        echo '<div class="col-md-40 col-sm-60 col-xs-120 content_first_item">
                                <div class="content_index_img"><img src="../../../upload/images/'.$topGoods["photo"].'" /></div>
                                <div class="content_description"><a href="#">'.$topGoods["category_name"].'></a>
                                    <div class="content_tel_title"><a href="#">'.$topGoods["brand"]
                                                                                .' '.$topGoods["model_name"]
                                                                                .'</a></div>    
                                </div>
                                <p class="content_old_price"><s>'.($topGoods["old_price"] != 0?$topGoods["old_price"].' р':"").'</s></p>
                                <p class="content_price">'.$topGoods["price"].' р</p>
                                <div class="content_button_buy" id="submit_rnd'.$topGoods['id'].'">'.($topGoods['quantity']>0?'Купить':'Заказать').'</div>   
                            </div>
                            <script>
                                $(\'body\').on(\'click\',\'#submit_rnd'.$topGoods['id'].'\',function(){
                                $.ajax({
                                    \'type\':\'POST\',
                                    \'dataType\':\'json\',
                                    \'success\':function(data){
                                        $("#count_update").text(data[1]);
                                        $("#sum_update").text(data[0]);
                                        $(\'.index_modal_dialog\').css({\'display\': \'block\', \'opacity\': \'0\'}).delay(500).animate({opacity: 0.6}, 300).delay(1500).animate({opacity: 0}, 300).css({\'display\': \'block\'});
                                    },
                                    \'url\':\'/main/index2\',
                                    \'cache\':false,
                                    \'data\': ({\'id\':'.$topGoods['id'].'})})
                            });
                            </script>
                            ';
                    }
                    
                    else if ($j == 1)
                    {
                        echo '<div class="col-md-40 col-sm-60 col-xs-120 content_second_item">
                                <div class="content_index_img"><img src="../../../upload/images/'.$topGoods["photo"].'" /></div>
                                <div class="content_description"><a href="#">'.$topGoods["category_name"].'></a>
                                    <div class="content_tel_title"><a href="#">'.$topGoods["brand"]
                                                                                .' '.$topGoods["model_name"]
                                                                                .'</a></div>    
                                </div>
                                <p class="content_old_price"><s>'.($topGoods["old_price"] != 0?$topGoods["old_price"].' р':"").'</s></p>
                                <p class="content_price">'.$topGoods["price"].' р</p>
                                <div class="content_button_buy" id="submit_rnd'.$topGoods['id'].'">'.($topGoods['quantity']>0?'Купить':'Заказать').'</div>   
                            </div>
                            <script>
                                $(\'body\').on(\'click\',\'#submit_rnd'.$topGoods['id'].'\',function(){
                                $.ajax({
                                    \'type\':\'POST\',
                                    \'dataType\':\'json\',
                                    \'success\':function(data){
                                        $("#count_update").text(data[1]);
                                        $("#sum_update").text(data[0]);
                                        $(\'.index_modal_dialog\').css({\'display\': \'block\', \'opacity\': \'0\'}).delay(500).animate({opacity: 0.6}, 300).delay(1500).animate({opacity: 0}, 300).css({\'display\': \'block\'});
                                    },
                                    \'url\':\'/main/index2\',
                                    \'cache\':false,
                                    \'data\': ({\'id\':'.$topGoods['id'].'})})
                            });
                            </script>
                            ';
                    }
                    
                    else if ($j == 2)
                    {
                        echo '<div class="col-md-40 col-sm-60 col-xs-120 content_third_item">
                                <div class="content_index_img"><img src="../../../upload/images/'.$topGoods["photo"].'" /></div>
                                <div class="content_description"><a href="#">'.$topGoods["category_name"].'></a>
                                    <div class="content_tel_title"><a href="#">'.$topGoods["brand"]
                                                                                .' '.$topGoods["model_name"]
                                                                                .'</a></div>    
                                </div>
                                <p class="content_old_price"><s>'.($topGoods["old_price"] != 0?$topGoods["old_price"].' р':"").'</s></p>
                                <p class="content_price">'.$topGoods["price"].' р</p>
                                <div class="content_button_buy" id="submit_rnd'.$topGoods['id'].'">'.($topGoods['quantity']>0?'Купить':'Заказать').'</div>   
                            </div>
                            <script>
                                $(\'body\').on(\'click\',\'#submit_rnd'.$topGoods['id'].'\',function(){
                                $.ajax({
                                    \'type\':\'POST\',
                                    \'dataType\':\'json\',
                                    \'success\':function(data){
                                        $("#count_update").text(data[1]);
                                        $("#sum_update").text(data[0]);
                                        $(\'.index_modal_dialog\').css({\'display\': \'block\', \'opacity\': \'0\'}).delay(500).animate({opacity: 0.6}, 300).delay(1500).animate({opacity: 0}, 300).css({\'display\': \'block\'});
                                    },
                                    \'url\':\'/main/index2\',
                                    \'cache\':false,
                                    \'data\': ({\'id\':'.$topGoods['id'].'})})
                            });
                            </script>
                            ';
                    }
                    
                    else if ($j == 3)
                    {
                        echo '<div class="col-md-40 col-sm-60 col-xs-120 content_fourth_item">
                                <div class="content_index_img"><img src="../../../upload/images/'.$topGoods["photo"].'" /></div>
                                <div class="content_description"><a href="#">'.$topGoods["category_name"].'></a>
                                    <div class="content_tel_title"><a href="#">'.$topGoods["brand"]
                                                                                .' '.$topGoods["model_name"]
                                                                                .'</a></div>    
                                </div>
                                <p class="content_old_price"><s>'.($topGoods["old_price"] != 0?$topGoods["old_price"].' р':"").'</s></p>
                                <p class="content_price">'.$topGoods["price"].' р</p>
                                <div class="content_button_buy" id="submit_rnd'.$topGoods['id'].'">'.($topGoods['quantity']>0?'Купить':'Заказать').'</div>   
                            </div>
                            <script>
                                $(\'body\').on(\'click\',\'#submit_rnd'.$topGoods['id'].'\',function(){
                                $.ajax({
                                    \'type\':\'POST\',
                                    \'dataType\':\'json\',
                                    \'success\':function(data){
                                        $("#count_update").text(data[1]);
                                        $("#sum_update").text(data[0]);
                                        $(\'.index_modal_dialog\').css({\'display\': \'block\', \'opacity\': \'0\'}).delay(500).animate({opacity: 0.6}, 300).delay(1500).animate({opacity: 0}, 300).css({\'display\': \'block\'});
                                    },
                                    \'url\':\'/main/index2\',
                                    \'cache\':false,
                                    \'data\': ({\'id\':'.$topGoods['id'].'})})
                            });
                            </script>    
                            ';
                    }
                    
                    else
                    {
                        break;
                    }
                    
                }
            ?>
        </div>
    </div>
</div> 
<?php echo CHtml::endForm();?>
<script type="text/javascript" src="//vk.com/js/api/openapi.js?121"></script>
<div class="row content_news">
    <div class="row">
        <div class="col-md-80 col-sm-120 col-xs-120 content_news_cont">
            <div class="content_news_header">
                <p class="text-uppercase">Новости</p>
            </div>
            <div class="row content_news_text">
                <!--
                <div class="col-md-60 col-sm-60 col-xs-120 content_news_news1">
                    <h3>18 декабря состоялось открытие нашего нового магазина по адресу</h3>
                    <p class="content_news_date">21 дек 2016</p>
                    <p class="content_news_textnews">18 декабря состоялось открытие нашего нового магазина по адресу: г. Донецк, ул. Стадионная 3д (Амстор 2 этаж). В период с 18 декабря по 31 декабря включительно в магазине на стадионной проходила акция, по итогом которой было разыграно 2 телефона и наушники.</p>
                </div>
                <div class="col-md-60 col-sm-60 hidden-xs content_news_news2">
                    <h3>18 декабря состоялось открытие нашего нового магазина по адресу</h3>
                    <p class="content_news_date">21 дек 2016</p>
                    <p class="content_news_textnews">18 декабря состоялось открытие нашего нового магазина по адресу: г. Донецк, ул. Стадионная 3д (Амстор 2 этаж). В период с 18 декабря по 31 декабря включительно в магазине на стадионной проходила акция, по итогом которой было разыграно 2 телефона и наушники.</p>
                </div>-->
                
                <?php foreach ($news as $n=>$text_news)
                      {
                        if($n == 0)
                        {
                            echo '<div class="col-md-60 col-sm-60 col-xs-120 content_news_news1">
                                      <h3>'.ShortNewsHelper::getShortTextNews($text_news->title, 7).'</h3>
                                      <p class="content_news_date">'.date("j.m.Y" , $text_news->date).'</p>
                                      <p class="content_news_textnews">'.ShortNewsHelper::getShortTextNews($text_news->text, 50).'</p>
                                  </div>';
                        }
                        else if($n == 1)
                        {
                            echo '<div class="col-md-60 col-sm-60 col-xs-120 content_news_news1">
                                      <h3>'.$text_news->title.'</h3>
                                      <p class="content_news_date">'.date("j.m.Y" , $text_news->date).'</p>
                                      <p class="content_news_textnews">'.ShortNewsHelper::getShortTextNews($text_news->text, 50).'</p>
                                  </div>';
                        }
                        else
                        {
                            break;
                        }
                        
                            
                      }  
                ?>
            </div>
        </div>
        <div class="col-md-40 col-sm-120 col-xs-120 content_news_widget">
            <!-- VK Widget -->
            <div id="vk_groups"></div>
            <script type="text/javascript">
            VK.Widgets.Group("vk_groups", {mode: 0, width: "240", height: "260", color1: 'FFFFFF', color2: '2B587A', color3: '5B7FA6'}, 79938946);
            </script>
        </div>
    </div>
</div>
 

