<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/news.style.css" rel="stylesheet"/>

<div class="row news_wrap">
    <div class="row news_contaner">
        <div class="row newsAll_contaner">
            <div class="col-md-120 col-sm-120 col-xs-120 text_header">
                <h1 class="text-uppercase"><?php echo ($model->id==1 ? 'О компании' : 
                                                      ($model->id==2 ? 'Доставка и оплата' :
                                                      ($model->id==3 ? 'Контакты' : 
                                                      ($model->id==4 ? 'Сотрудничество' :
                                                      ($model->id==5 ? 'Проверить статус заказа' :
                                                      ($model->id==6 ? 'Забери товар в ближайшем магазине' :
                                                      ''))))));       
                                           ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-120 col-sm-120 col-xs-120 text_content">
                <?php echo $model->text; ?>
            </div>
        </div>
    </div>
</div>     