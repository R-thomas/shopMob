<?php

$this->menu=array(
	array('label'=>'Все акции', 'url'=>array('index')),
	array('label'=>'Менеджер акций', 'url'=>array('admin')),
);
?>

<h1>Добавить ацию</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>