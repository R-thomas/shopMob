<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="ru"/>

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print">
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection">
	<![endif]-->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css">
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.12.0.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.min.js"></script>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
            'submenuHtmlOptions' => array('class' => 'dropdown-menu'),
			'items'=>array(
				array('label'=>'К сайту', 
                      'url'=>array('/main/index')),
                array('label'=>'Заказы', 
                      'url'=>array('/admin/orders'), 
                      'active' => Yii::app()->controller->id == 'orders'),  
                array('label'=>'Категории', 
                      'url'=>array('/admin/category'), 
                      'active' => Yii::app()->controller->id =='category' || 
                                  Yii::app()->controller->id =='characteristics'),
                array('label'=>'Товары', 
                      'url'=>array('/admin/goods'), 
                      'active' => Yii::app()->controller->id == 'goods' || 
                                  Yii::app()->controller->id =='modelCategory' || 
                                  Yii::app()->controller->id =='models'),
                array('label'=>'Загрузка/Выгрузка', 
                      'url'=>array('/admin/download'), 
                      'active' => Yii::app()->controller->id == 'download'),                  
                array('label'=>'Слайдер', 
                      'url'=>array('/admin/banner'), 
                      'active' => Yii::app()->controller->id == 'banner'),  
                array('label'=>'Новости', 
                      'url'=>array('/admin/news'), 
                      'active' => Yii::app()->controller->id == 'news'),   
                array('label'=>'Акции', 
                      'url'=>array('/admin/promotion'), 
                      'active' => Yii::app()->controller->id == 'promotion'),
                array('label'=>'Текстовые страницы', 
                      'url'=>array('#'),
                      'linkOptions' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown', 'role'=>'button', 'aria-haspopup'=>'true', 'aria-expanded'=>'false'),
                      'itemOptions'=>array('class'=>'dropdown'),
                      
                      'items' => array(
                            
                            array('label'=>'О компании', 
                                  'url'=>array('/admin/textPage/1'), 
                                  'active' => Yii::app()->controller->id == 'textPage' && $_GET['id'] == 1),   
                            array('label'=>'Доставка и оплата', 
                                  'url'=>array('/admin/textPage/2'), 
                                  'active' => Yii::app()->controller->id == 'textPage' && $_GET['id'] == 2),
                            array('label'=>'Контакты',
                                  'url'=>array('/admin/textPage/3'), 
                                  'active' => Yii::app()->controller->id == 'textPage' && $_GET['id'] == 3),
                            array('label'=>'Оптовым покупателям',
                                  'url'=>array('/admin/textPage/4'), 
                                  'active' => Yii::app()->controller->id == 'textPage' && $_GET['id'] == 4),
                            array('label'=>'Товар под заказ',
                                  'url'=>array('/admin/textPage/5'), 
                                  'active' => Yii::app()->controller->id == 'textPage' && $_GET['id'] == 5),
                            array('label'=>'Забери товар в ближайшем магазине',
                                  'url'=>array('/admin/textPage/6'), 
                                  'active' => Yii::app()->controller->id == 'textPage' && $_GET['id'] == 6),        
                      )),      
                                                                        
				array('label'=>'Войти', 
                      'url'=>array('/site/login'), 
                      'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Выйти ('.Yii::app()->user->name.')', 
                      'url'=>array('/site/logout'), 
                      'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
            'homeLink' => CHtml::link('Админ-панель', '/admin'),
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

</div><!-- page -->
<style>
ul.dropdown_menu li{
    display: block !important;
}
</style>
</body>
</html>
