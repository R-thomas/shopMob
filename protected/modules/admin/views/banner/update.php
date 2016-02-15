<?php

$this->menu=array(
	array('label'=>'Добавить баннер', 'url'=>array('create')),
	array('label'=>'Просмотр баннера', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Менеджер баннеров', 'url'=>array('index')),
);
?>

<h1>Редактирование баннера</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>