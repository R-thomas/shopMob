<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/goods.style.css" rel="stylesheet"/>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/index.style.css" rel="stylesheet"/>

<div class="row"></div>
<div class="row goods_content_wrap">
    <div class="col-md-120 col-sm-120 col-xs-120 goods_content">
        <div class="row goods_content_header">
            <div class="col-md-60 col-sm-120 col-xs-120 ">
                <h1 class="text-uppercase"><?php echo $category->category_name; ?></h1>
            </div>
            <div class="col-md-60 col-sm-120 col-xs-120 ">
                <div class="goods_content_header_select"> 
                    <span class="goods_select_label">Сортировка</span>
                    <?php echo CHtml::form('/main/goods/category_id/'.$_GET['category_id'], 'get', array('class'=>'goods_form_desc')) ?>
                    <select id="desc" name="desc">
                        <option value="0"></option>
                        <option value="2" <?php echo (isset($_GET['desc']) && $_GET['desc']==2 ? 'selected' : '') ?>>От дешевых к дорогим</option>
                        <option value="1" <?php echo (isset($_GET['desc']) && $_GET['desc']==1 ? 'selected' : '') ?> >От дорогих к дешевым</option>
                        
                    </select>
                    <?php echo CHtml::endForm() ?>
                </div>
            </div>
        </div>
        <div class="row goods_filter_header" id="goods_filter_header">
            <div class="hidden-lg hidden-md col-sm-120 col-xs-120">
                <p>Параметры <span class="glyphicon glyphicon-triangle-bottom"></span></p>
            </div>
        </div>
        <div class="row goods_content_container">
        
        
            <div class="col-md-40 col-sm-120 col-xs-120 goods_filter">
                
                <a href="<?php echo $this->createAbsoluteUrl('main/goods/category_id/'.$category_id); ?>"><div class="filter_selected_remove_all" <?php echo ''.(($a != '') ? '' : 'style="display:none;"').'';?> >
                    Сбросить все фильтры
                </div></a>
                
                
                <?php 
                    echo CHtml::form('', 'get', array('id'=>'my_form', 'name'=>'person'));
                	echo Characteristics::filterRender($category_id, $brand_name, $count, $count_maker, $count_top, $count_promotion, $count_novelty, $count_bestPrice);
                    echo CHtml::submitButton('Отправить' , array('style' => 'display:none'));
                    echo CHtml::endForm();
                ?>
                <script>
                    //console.log(parseGetParams());
                    //document.person.action='/main/goods/category_id/1?common[]=top&common[]=promotion'
                </script>
                <!--
                <div class="goods_filter_selected">
                    <p>Экран <span class="glyphicon glyphicon-triangle-bottom"></p>
                </div>
                <div class="filter_selected">
                    <div class="selected_item">
                         <p>Диагональ</p>
                         <div>
                             <span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">5 дюймов</span>
                         </div>
                         <div>
                             <span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">5,5 дюймов</span>
                         </div>
                         <div>
                             <span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">4,5 дюймов</span>
                         </div>
                         
                         <p>Разрешение</p>
                         <div>
                             <span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">1920Х1080 пикс</span>
                         </div>
                         <div>
                             <span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">1024Х768 пикс</span>
                         </div>
                         <div>
                             <span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">728Х320 пикс</span>
                         </div>
                    </div>
                    
                </div>
                
                <div class="goods_filter_selected">
                    <p>Батарея <span class="glyphicon glyphicon-triangle-bottom"></p>
                </div>
                <div class="filter_selected">
                    <div class="selected_item">
                         <p>Емкость</p>
                         <div>
                             <span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">2000 мАч</span>
                         </div>
                         <div>
                             <span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">3000 мАч</span>
                         </div>
                         <div>
                             <span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">4000 мАч</span>
                         </div>
                         
                         <p>Время работы</p>
                         <div>
                             <span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">20 ч</span>
                         </div>
                         <div>
                             <span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">30 ч</span>
                         </div>
                    </div>
                    
                </div>-->
            </div>
            <!-- Товары -->
            <div class="col-md-80 col-sm-120 col-xs-120 goods_content_goods" id="goods_content_goods">
                <div class="row">
                    
                    <?php echo CHtml::form();?>
                    <?php 
                          $j = 1;  
                          foreach ($model as $goods)
                          {
                            
                            if ($j == 1)
                            {
                                
                                echo '<a href="/main/product/'.$goods->id.'"><div class="col-md-60 col-sm-60 col-xs-120 content_first_item goods_content_first_item">
                                          <div class="content_index_img">';
                                    $i = 0;
                                    if($goods->top==1)
                                    {
                                        echo '<img src="../../../images/top.png" class="stiker'.($i == 0 ? "1" : ($i == 1 ? "2" : ($i == 2 ? "3" : "4"))).'" />';
                                        $i++;
                                    }
                                    
                                    if($goods->promotion==1)
                                    {
                                        echo '<img src="../../../images/promotion.png" class="stiker'.($i == 0 ? "1" : ($i == 1 ? "2" : ($i == 2 ? "3" : "4"))).'" />';
                                        $i++;
                                    }
                                    
                                    if($goods->novelty==1)
                                    {
                                        echo '<img src="../../../images/novelty.png" class="stiker'.($i == 0 ? "1" : ($i == 1 ? "2" : ($i == 2 ? "3" : "4"))).'" />';
                                        $i++;
                                    }
                                    
                                    if($goods->bestPrice==1)
                                    {
                                        echo '<img src="../../../images/best_price.png" class="stiker'.($i == 0 ? "1" : ($i == 1 ? "2" : ($i == 2 ? "3" : "4"))).'" />';
                                        $i++;
                                    }
                                    
                                    
                                    
                                    
                                    echo '<img src="../../../upload/images/'.$goods->photo.'" /></div>
                                          <div class="content_description"><a href="/main/goods/category_id/'.$goods->categoryId->category->id.'">'.$goods->categoryId->category->category_name.'></a>
                                              <div class="content_tel_title"><a href="/main/product/'.$goods->id.'">'.$goods->model_name.'</a></div>    
                                          </div>
                                          <p class="content_old_price"><s>'.($goods->old_price != 0?$goods->old_price.' р':"").'</s></p>
                                          <p class="content_price">'.$goods->price.' р</p>
                                          <div class="content_button_buy" id="submit'.$goods->id.'">'.($goods->quantity>0?'Купить':'Заказать').'</div>   
                                      </div></a>
                                        <script>
                                            $(\'body\').on(\'click\',\'#submit'.$goods->id.'\',function(){
                                                $(\'.loader\').css({\'display\':\'block\'});
                                            $.ajax({
                                                \'type\':\'POST\',
                                                \'dataType\':\'json\',
                                                \'success\':function(data){
                                                    $("#count_update").text(data[1]);
                                                    $("#sum_update").text(data[0]);
                                                    $(\'.index_modal_dialog\').css({\'display\': \'block\', \'opacity\': \'0\'}).delay(500).animate({opacity: 0.6}, 300).delay(1500).animate({opacity: 0}, 300).css({\'display\': \'block\'});
                                                    $(\'.loader\').css({\'display\':\'none\'});
                                                },
                                                \'url\':\'/main/index\',
                                                \'cache\':false,
                                                \'data\': ({\'id\':'.$goods->id.'})})
                                        });
                                        </script>
                                      ';
                            }
                            
                            
                            
                            if ($j == 2)
                            {
                                $j = 0;
                                echo '<a href="/main/product/'.$goods->id.'"><div class="col-md-60 col-sm-60 col-xs-120 content_first_item goods_content_second_item">
                                      <div class="content_index_img">';
                                    $i = 0;
                                    if($goods->top==1)
                                    {
                                        echo '<img src="../../../images/top.png" class="stiker'.($i == 0 ? "1" : ($i == 1 ? "2" : ($i == 2 ? "3" : "4"))).'" />';
                                        $i++;
                                    }
                                    
                                    if($goods->promotion==1)
                                    {
                                        echo '<img src="../../../images/promotion.png" class="stiker'.($i == 0 ? "1" : ($i == 1 ? "2" : ($i == 2 ? "3" : "4"))).'" />';
                                        $i++;
                                    }
                                    
                                    if($goods->novelty==1)
                                    {
                                        echo '<img src="../../../images/novelty.png" class="stiker'.($i == 0 ? "1" : ($i == 1 ? "2" : ($i == 2 ? "3" : "4"))).'" />';
                                        $i++;
                                    }
                                    
                                    if($goods->bestPrice==1)
                                    {
                                        echo '<img src="../../../images/best_price.png" class="stiker'.($i == 0 ? "1" : ($i == 1 ? "2" : ($i == 2 ? "3" : "4"))).'" />';
                                        $i++;
                                    }
                                    
                                    
                                    
                                    
                                    echo '<img src="../../../upload/images/'.$goods->photo.'" /></div>
                                      <div class="content_description"><a href="/main/goods/category_id/'.$goods->categoryId->category->id.'">'.$goods->categoryId->category->category_name.'></a>
                                          <div class="content_tel_title"><a href="/main/product/'.$goods->id.'">'.$goods->model_name.'</a></div>    
                                      </div>
                                      <p class="content_old_price"><s>'.($goods->old_price != 0?$goods->old_price.' р':"").'</s></p>
                                      <p class="content_price">'.$goods->price.' р</p>
                                      <div class="content_button_buy" id="submit'.$goods->id.'">'.($goods->quantity>0?'Купить':'Заказать').'</div>   
                                  </div></a>
                                  <script>
                                        $(\'body\').on(\'click\',\'#submit'.$goods->id.'\',function(){
                                            $(\'.loader\').css({\'display\':\'block\'});
                                        $.ajax({
                                            \'type\':\'POST\',
                                            \'dataType\':\'json\',
                                            \'success\':function(data){
                                                $("#count_update").text(data[1]);
                                                $("#sum_update").text(data[0]);
                                                $(\'.index_modal_dialog\').css({\'display\': \'block\', \'opacity\': \'0\'}).delay(500).animate({opacity: 0.6}, 300).delay(1500).animate({opacity: 0}, 300).css({\'display\': \'block\'});
                                                $(\'.loader\').css({\'display\':\'none\'});
                                            },
                                            \'url\':\'/main/index\',
                                            \'cache\':false,
                                            \'data\': ({\'id\':'.$goods->id.'})})
                                    });
                                    </script>
                                  ';
                            }
                            
                            
                            $j++;
                          }  
                    
                    ?>
                    <?php echo CHtml::endForm();?>
                    
               </div> 
               <?$this->widget('CLinkPager', array(
                        'pages' => $pages,
                        'header' => false,
                        'nextPageLabel' => '>',
                        'nextPageCssClass' => 'goods_next_page',
                        'prevPageLabel' => '<',
                        'previousPageCssClass' => 'goods_prev_page',
                        'selectedPageCssClass' => 'goods_selected_page',
                        'internalPageCssClass' => 'goods_internal_page',
                        'maxButtonCount' => 7,
                    ))?>
            </div>
        </div>
    </div>
</div>

<div><img src="../../../images/KFLtA.png" class="loader" width="100"  /></div>
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