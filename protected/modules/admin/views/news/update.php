<?php

$this->menu=array(
	array('label'=>'Все новости', 'url'=>array('index')),
	array('label'=>'Добавить новость', 'url'=>array('create')),
	array('label'=>'Просмотреть новость', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Менеджер новостей', 'url'=>array('admin')),
);
?>

<h1>Редактирование новости <?php echo $model->title; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>