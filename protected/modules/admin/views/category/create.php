<?php


$this->menu=array(
	array('label'=>'Менеджер категорий', 'url'=>array('index')),
);
?>

<h1>Создать категорию</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>