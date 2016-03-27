<?php

$this->menu=array(
	array('label'=>'Редактировать', 'url'=>array('update', 'id'=>$model->id))
);
?>

<h1><?php echo ($model->id==1 ? 'О компании' : 
                ($model->id==2 ? 'Доставка и оплата' :
                ($model->id==3 ? 'Контакты' : 
                '')));       
?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'text',
	),
)); ?>
