<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/news.style.css" rel="stylesheet"/>

<div class="row news_wrap">
    <div class="row news_contaner">
        <div class="row newsAll_contaner">
            <div class="col-md-120 col-sm-120 col-xs-120 news_header">
                <h1 class="text-uppercase">Акции</h1>
            </div>
        </div>
        
        <?php
            foreach($model as $item)
            { 
                echo '<div class="row promotion_item">
                        
                        <div class="col-md-120 col-sm-120 col-xs-120 promotion_text_container">
                            <a href="/main/promotion/'.$item->id.'">
                                <h2>'.$item->title.'</h2>
                            </a>
                        </div>
                
                        <a href="/main/promotion/'.$item->id.'">
                            <div class="col-md-120 col-sm-120 col-xs-120 promotion_img_contaner">
                                <img src="../../../upload/images/'.$item->img.'" class="img-responsive" />
                            </div>
                        </a>
                        
                    </div>
                    ';
                        }	
        ?>
        
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