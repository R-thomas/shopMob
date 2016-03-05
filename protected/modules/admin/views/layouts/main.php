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

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css">

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
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
				array('label'=>'Login', 
                      'url'=>array('/site/login'), 
                      'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 
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

</body>
</html>
