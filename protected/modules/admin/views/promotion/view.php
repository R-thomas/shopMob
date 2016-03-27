<?php

$this->menu=array(
	array('label'=>'Все акции', 'url'=>array('index')),
	array('label'=>'Добавить акцию', 'url'=>array('create')),
	array('label'=>'Редактировать акцию', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить акцию', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Менеджер акций', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->title; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'text',
        'img' => array(
            'name'=>'img',
            'type'=>'raw',
            'value'=>CHtml::image("/upload/images/" . $model->img, 
                                  "Нет изображения - загрузите изображение", 
                                  array("style" => "width:600px")))
	),
)); ?>
