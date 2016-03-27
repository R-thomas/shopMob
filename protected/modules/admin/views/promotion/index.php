<?php

$this->menu=array(
	array('label'=>'Добавить акцию', 'url'=>array('create')),
	array('label'=>'Менеджер акций', 'url'=>array('admin')),
);
?>

<h1>Акции</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
