<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <!-- Bootstrap -->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css" rel="stylesheet"/>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/common.css" rel="stylesheet"/>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.12.0.min.js"></script>
  </head>
  <body>
    <div class="wrapper">
        <div class="container-fluid content_my">
            <header>
                <div class="menu_top">
                    <div class="collapse navbar-collapse navbar_top">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="#" class="navbar_top">О компании</a></li>
                            <li><a href="#" class="navbar_top">Адреса Магазинов</a></li>
                            <li><a href="#" class="navbar_top">Доставка и оплата</a></li>
                            <li><a href="#" class="navbar_top">Контакты</a></li>
                            <li><a href="#" class="navbar_top">Акции</a></li>
                        </ul>
                    </div>
                </div>
                <div class="header_middle">
                    <div class="col-md-40 col-sm-50 col-xs-120 logo_main">
                        <div class="header_logo"><a href="<?php echo Yii::app()->request->baseUrl; ?>/main/index"><img src="../../../images/logo.png" class="img-responsive"/></a></div>
                    </div>
                    <div class="col-md-20 hidden-sm hidden-xs header_tel">
                        099-79-29-449<br />
                        050-650-11-15<br />
                        073-213-48-49
                    </div>
                    <div class="hidden-lg hidden-md col-sm-offset-16 col-sm-12 col-xs-30  header_geo">
                        <div class="header_geo_cont">
                            <div class="header_geo_img"><a href="#"><img src="../../../images/geo.png" class="img-responsive"/></a></div>
                        </div>
                    </div>
                    <div class="hidden-lg hidden-md col-sm-12 col-xs-30  header_geo">
                        <div class="header_geo_cont">
                            <div class="header_geo_img"><a href="#modal_tel" data-toggle="modal"><img src="../../../images/tel_large.png" class="img-responsive"/></a></div>
                        </div>
                    </div>
                    
                    <div class="col-md-20 col-md-push-40 col-sm-12 col-xs-30 header_cart">
                        <div class="header_cart_cont">
                            <a href="<?php echo Yii::app()->request->baseUrl; ?>/main/cart">
                            <div class="header_cart_count"><span class="count" id="count_update"><?php echo Yii::app()->shoppingCart->getCount(); ?></span><img src="../../../images/cart.png" class="img-responsive"/></div>
                            <div class="header_cart_text"><span class="header_cart_link">Корзина</span><br /><span id="sum_update"><?php echo Yii::app()->shoppingCart->getCost(); ?></span> руб</div>
                            </a>
                        </div>
                    </div>
                    <div class="hidden-lg hidden-md col-sm-18 col-xs-30 header_geo">
                        <div class="header_geo_cont menu_ico">
                            <div class="header_geo_img"><img src="../../../images/menu.png" class="img-responsive"/></div>
                        </div>
                    </div>
                    <div class="col-md-40 col-md-pull-20 col-sm-120 col-xs-120 header_search">
                        <input type="text" placeholder="Поиск" class="header_input"/>
                        <input type="button" value="Поиск" class="header_submit"/>
                        <div class="header_a_order"><a href="#">Заказать обратный звонок</a></div>
                    </div>
                </div>
                <nav>
                    <div class="header_nav" id="header_nav_toggle">
                        <ul class="menu">
                            <li class="nav_items"><a href="#">Телефоны</a></li>
                            <li class="nav_items"><a href="#">Планшеты</a></li>
                            <li class="nav_items"><a href="#">Ноутбуки</a></li>
                            <li class="nav_items"><a href="#">Асессуары</a></li>
                            <li class="nav_items"><a href="#">Портативная техника</a></li>
                            <li class="nav_items"><a href="#">Носители информации</a></li>
                            <li><a href="#">Услуги</a></li>
                        </ul>
                    </div>
                </nav>
                
            </header>
            
            
            
            <?php echo $content; ?>
            <div class="index_modal_dialog"><div class="index_modal_dialog_inner"><p>Товар добавлен в корзину!</p></div></div>
            <div class="row seo_text">
                <div class="col-md-120 col-sm-120 col-xs-120">
                    <p>Добро пожаловать в интернет – магазин цифровой техники "Мобильный мир"!</p>
                </div>
            </div>
            
            <footer>
                <div class="footer_top">
                    <div class="col-md-40 col-sm-40 col-xs-40 footer_first_item">
                        <p>Интернет-магазин «Мобильный мир» г. Донецк, ул. Стадионная 3д</p>
                    </div>
                    <div class="col-md-20 col-sm-25 col-xs-25">
                        <p><a href="#">О компании</a><br /><a href="#">Адреса магазинов</a><br /><a href="#">Сотрудничество</a><br /><a href="#">Вакансии</a></p>
                    </div>
                    <div class="col-md-20 col-sm-25 col-xs-25">
                        <p><a href="#">Контакты</a><br /><a href="#">Каталог</a><br /><a href="#">Оплата и доставка</a><br /><a href="#">Акции</a></p>
                    </div>
                    <div class="col-md-20 col-sm-30 col-xs-30">
                        <p>Телефоны для справок:<br />099-79-29-449<br />050-650-11-15<br />073-213-48-49</p>
                    </div>
                    <div class="col-md-20 hidden-sm hidden-xs">
                        <p>Мы в соц.сетях</p>
                        <a href="http://vk.com" target="_blank"><img src="../../../images/vk.png" width="21" height="22" /></a>
                    </div>
                </div>
                <div class="footer_bottom">
                    <div class="col-md-60 col-sm-60 col-xs-60">
                        <p>Мобильный мир 2015</p>
                    </div>
                    <div class="col-md-60 col-sm-60 col-xs-60 text-right">
                        <p>Разработка сайтов <a href="http://keysmm.ru" target="_blank">KEYSMM</a></p>
                    </div>
                </div>
            </footer>
            
            <div id="modal_tel" class="modal fade" tabindex="-1">
                <div class="modal-dialog modal_dialog">
                    <div class="modal-header">
                        <button type="button" class="close glyphicon glyphicon-remove" data-dismiss="modal"></button>
                        <h3>Контактные телефоны</h3>
                    </div>
                    <div class="modal-body">
                        <p>099-79-29-449</p>
                        <p>050-650-11-15</p>
                        <p>073-213-48-49</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- de all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/myscript.js"></script>
  </body>
</html>
