<?php if(Yii::app()->user->hasFlash('status')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('status'); ?>
</div>

<?php endif; ?>

<?php $this->menu=array(
	array('label'=>'Управление моделями', 'url'=>array('/admin/models/index/category/'.$category.'/brand/'.$brand)),
);
?>

<h1>Создание модели</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'modelCharVal'=>$modelCharVal, 'modelChar'=>$modelChar)); ?>