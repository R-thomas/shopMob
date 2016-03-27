<?php

$this->menu=array(
	array('label'=>'Все акции', 'url'=>array('index')),
	array('label'=>'Добавить акцию', 'url'=>array('create')),
	array('label'=>'Просмотреть акцию', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Менеджер акций', 'url'=>array('admin')),
);
?>

<h1>Редактирование новости <?php echo $model->title; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>