<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/news.style.css" rel="stylesheet"/>

<div class="row news_wrap">
    <div class="row news_contaner">
        <div class="row newsAll_contaner">
            <div class="col-md-120 col-sm-120 col-xs-120 news_header">
                <h1 class="text-uppercase">Новости</h1>
            </div>
        </div>
        <div class="row news_page_container">
            <div class="col-md-60 col-sm-120 col-xs-120 news_page_container_img">
                <img src="../../../upload/images/<?php echo $model->img; ?>" />
            </div>
            <div class="hidden-lg hidden-md col-sm-120 col-xs-120">
                <p class="news_space"></p>
            </div>
            <h1><?php echo $model->title; ?></h1>
            <p class="news_date"><?php echo $model->date; ?></p>
            <div class="news_text">
                <p><?php echo $model->text; ?></p>
            </div>
        </div>
        <div class="row">
            <div class="news_button news_button_back">
                <p>Вернуться назад</p>
            </div>
        </div>
        
        <script>
            $('.news_button').on('click', function(){
                history.go(-1);
            })
        </script>
    </div>
</div>        