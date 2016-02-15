<?php

$this->menu=array(
	array('label'=>'Убравление баннерами', 'url'=>array('index')),
);
?>

<h1>Добавить баннер</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>