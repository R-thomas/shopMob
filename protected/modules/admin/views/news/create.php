<?php

$this->menu=array(
	array('label'=>'Все новости', 'url'=>array('index')),
	array('label'=>'Менеджер новостей', 'url'=>array('admin')),
);
?>

<h1>Добавить новость</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>